#!/usr/bin/python
import socket
import threading 
import hl7
import mysql.connector
import datetime
import logging

db_user_name = "root"
db_user_password ="gpitg2014"
db_name ="bugando_new"

logging.basicConfig(filename='Hl7.log',level=logging.DEBUG)
dates = str(datetime.datetime.now())
#bind_ip   = socket.gethostname()
bind_ip = '0.0.0.0'
bind_port = 5600
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 
server.bind((bind_ip,bind_port)) 
server.listen(5)  
logging.info("["+dates+"] Listening on %s:%d" % (bind_ip,bind_port))
print("working.......")
def handle_client(client_socket):      
    request = client_socket.recv(16384)  
    logging.info("["+dates+"] Received data from the machine ...")    
    data = hl7.parse(request)
    for item in process_info(data):
        try:
            #with open(str(datetime.datetime.now().strftime("%Y%m%d%H%M%S"))+".txt",'wb') as file:
            #    file.write(request)
            #file.close()
            #logging.info("["+dates+"] data writen to file ...")
            push_to_database(item[0].split("^")[1],item[1].lstrip(), item[2].lstrip(), item[3].lstrip(),get_statue(item[2].lstrip(),item[1].lstrip()),item[4],item[5],item[6])
            logging.info("["+dates+"] data pushed to database ...")
        except Exception as ex:
            logging.error(ex)  
    client_socket.send("ACK!".encode())
    logging.info("["+dates+"] Send aknowledgment to the Machine ...")      
    client_socket.close()

def process_info(data):
    list_data = []
    sampleID = ""
    for i in range(0,len(data) - 1):
        if ("OBR") in str(data[i]):
            sampleID = str(data[i][3])
        if ("OBX" and "NM") in str(data[i]):
            if ("OBX" and "NM" and "Age") in str(data[i]):
                pass
            else:
                list_data.append([str(data[i][3]),str(data[i][5]),str(data[i][7]),str(data[i][6]),sampleID,str(data[0][3]),dates])
                logging.info("["+dates+"] List data Processed Succesfull ...")
    logging.info("["+dates+"] Process Information succesfull..")
    return list_data

def get_statue(rangevalue,testdata):
    status = " "
    rage = rangevalue.rstrip()
    upper_range = float(rage.split("-")[1])
    lower_range = float(rage.split("-")[0])
    if float(testdata) < lower_range:
        status = "L"
    elif float(testdata) > upper_range:
        status = "H"
    else:
        status = "N"
    return status

def push_to_database(parameter, result, refrange,unit,status,sampleID,machinetype,date):
    mydb = mysql.connector.connect(
        host="localhost",
        user=db_user_name,
        passwd=db_user_password,
        database= db_name
    )
    try:
        mycursor = mydb.cursor()
        sql = "INSERT INTO tbl_intergrated_lab_results (parameters, results, reference_range_normal_values,	units,status_h_or_l,sample_test_id,machine_type,result_date) VALUES (%s, %s,%s, %s,%s, %s,%s, %s)"
        val = (parameter, result, refrange,unit,status,sampleID,machinetype,date)
        mycursor.execute(sql, val)
        mydb.commit()
        logging.info("["+dates+"] Data commited to Database Succesfull ...")
    except Exception as e:
        logging.info("["+dates+"]",e) 

def process_date(data):
    dates = []
    for i in range(0,len(data) - 1):
        #if str(data[i]).startswith("OBX"):
        if ("OBR") in str(data[i]):
            dates = str(data[i][6])
    return dates

while True:
    client,addr = server.accept()
    server.setblocking(0)
    logging.info("["+dates+"] Accepted connection from: %s:%d" % (addr[0],addr[1]))
    client_handler = threading.Thread(target=handle_client,args=(client,))
    logging.info("["+dates+"] client thread is running ...")
    client_handler.start() 
