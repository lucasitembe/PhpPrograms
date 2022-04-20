//drop view employee_view;

CREATE  VIEW employee_view AS 
SELECT e.id AS id,e.EmployeeId AS EmployeeId,e.FirstName AS FirstName,e.MiddleName AS MiddleName,
e.LastName AS LastName,e.Sex AS Sex,e.dob AS dob,e.EducationLevel AS EducationLevel,e.Religion AS Religion,
e.MaritalStatus AS MaritalStatus,
CASE WHEN  ((SELECT promotion.Department FROM promotion WHERE e.id=promotion.Employee AND id=(SELECT MAX(promotion.id) FROM promotion WHERE e.id=promotion.Employee) ) >0)
         THEN  (SELECT promotion.Department FROM promotion WHERE e.id=promotion.Employee AND id=(SELECT MAX(promotion.id) FROM promotion WHERE e.id=promotion.Employee) )
          ELSE j.Department
       END AS Department,
CASE WHEN  ((SELECT promotion.Position FROM promotion WHERE e.id=promotion.Employee AND id=(SELECT MAX(promotion.id) FROM promotion WHERE e.id=promotion.Employee) ) >0)
         THEN  (SELECT promotion.Position FROM promotion WHERE e.id=promotion.Employee AND id=(SELECT MAX(promotion.id) FROM promotion WHERE e.id=promotion.Employee) )
          ELSE j.Position
       END AS Position,
CASE WHEN  ((SELECT promotion.WorkStation FROM promotion WHERE e.id=promotion.Employee AND id=(SELECT MAX(promotion.id) FROM promotion WHERE e.id=promotion.Employee) ) >0)
         THEN  (SELECT promotion.WorkStation FROM promotion WHERE e.id=promotion.Employee AND id=(SELECT MAX(promotion.id) FROM promotion WHERE e.id=promotion.Employee) )
          ELSE j.WorkStation
       END AS WorkStation,

j.ContractType AS ContractType,j.Enddate,s.Amount AS Amount,s.SalaryGrade,
CASE WHEN (FLOOR( DATEDIFF( NOW( ) , (SELECT employee.dob FROM employee WHERE employee.id=e.id) ) /365 )>55) 
         THEN 1 
    WHEN (j.Enddate < NOW()) THEN 1
          ELSE 0
       END AS Retere,
CASE WHEN (1>0) 
         THEN (FLOOR( DATEDIFF( NOW( ) , (SELECT employee.dob FROM employee WHERE employee.id=e.id) ) /365 )) 
          ELSE 0
       END AS Age,

c.Street,c.Postal,c.Email,c.Mobile,c.District,c.Region

FROM employee as e left join job as j on e.id = j.Employee  left join salary as s on e.id = s.Employee left join contact as c on e.id=c.Employee;





===============================================================================

//drop view loanclose_view;

CREATE  VIEW loanclose_view AS
SELECT l.id,l.Employeeid,l.Loan_Number,l.Employee,l.Loan_Amount,l.Month_D,l.Year_D,(SELECT SUM(pp.Amount) FROM loan_payment as pp  WHERE l.Employee=pp.Employee AND l.Loan_Number=pp.Loan AND l.delivery=1 ) as  Paid,
CASE WHEN ((SELECT SUM(pp.Amount) FROM loan_payment as pp  WHERE l.Employee=pp.Employee AND l.Loan_Number=pp.Loan AND l.delivery=1 ) >=(l.Loan_Amount))
 THEN 1
 ELSE 0
END AS is_close,
CASE WHEN (1>0)
THEN (l.Month_D+l.Year_D)
ELSE (l.Month_D+l.Year_D)
END AS TEST
FROM loan as l WHERE l.delivery=1;



==================================================================================
//drop view leave_view;

CREATE VIEW leave_view AS
SELECT l.id,l.Employee,l.LeaveType,l.Fromdate,l.Todate,l.is_active,l.is_approved,
DATEDIFF(l.Todate,l.Fromdate) as days,
CASE WHEN ((DATEDIFF(l.Todate,l.Fromdate) - DATEDIFF(NOW(),l.Fromdate) ) > 0)
 THEN (DATEDIFF(l.Todate,l.Fromdate) - DATEDIFF(NOW(),l.Fromdate) )
ELSE 0
END AS day_remain
FROM leave_info as l


=======================================================


/*trigger
*
* Tengeneza table yako may be home, yenye id, name etc
* tengene table nyingine ya kuweka value before update or before delete
* mfano : home_audit yenye id, action, oldid,oldname etc 
*/



/*
 *  Run query hii BEFORE DELETE ON HOME TABLE 
 *  Keep auditing trial for delete action
*/

DELIMITER $$
CREATE TRIGGER before_studentresult_delete
    BEFORE DELETE ON studentresult
    FOR EACH ROW BEGIN

    INSERT INTO studentresult_audit
    SET action_value = 'delete',
        id = OLD.id,
       RegNo = OLD.RegNo,
  AYear = OLD.AYear,
  Semester = OLD.Semester,
  CourseCode = OLD.CourseCode,
  ExamCategory = OLD.ExamCategory,
  Score = OLD.Score,
  publish = OLD.publish,
  createdby = OLD.createdby,
  createdon = OLD.createdon,
  modifiedby = OLD.modifiedby,
  modifiedon = OLD.modifiedon,
  publish_CA = OLD.publish_CA ,
  RPT = OLD.RPT;
END$$
DELIMITER ;


/* THEN RUN QUERY HII BEFORE UPDATE
 *  Keep auditing trial for update action
*/

DELIMITER $$
CREATE TRIGGER before_studentresult_update
    BEFORE UPDATE ON studentresult
    FOR EACH ROW 
BEGIN
IF (NEW.Score <> OLD.Score) THEN
    INSERT INTO studentresult_audit
    SET action_value = 'update',
        id = OLD.id,
       RegNo = OLD.RegNo,
  AYear = OLD.AYear,
  Semester = OLD.Semester,
  CourseCode = OLD.CourseCode,
  ExamCategory = OLD.ExamCategory,
  Score = OLD.Score,
  publish = OLD.publish,
  createdby = OLD.createdby,
  createdon = OLD.createdon,
  modifiedby = OLD.modifiedby,
  modifiedon = OLD.modifiedon,
  publish_CA = OLD.publish_CA ,
  RPT = OLD.RPT;
     	END IF;
END$$
DELIMITER;





/*DROP TREGGER RUN THIS QUERY*/
DROP TRIGGER before_home_delete


/* COPY DATA FROM ONE TABLE TO ANOTHER TABLE*/
INSERT INTO home_audit(name) SELECT name FROM home;

//
CREATE TABLE IF NOT EXISTS `studentresult_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
   `action_value` varchar(45) DEFAULT NULL,
  `RegNo` varchar(45) DEFAULT NULL,
  `AYear` varchar(45) DEFAULT NULL,
  `Semester` varchar(45) DEFAULT NULL,
  `CourseCode` varchar(45) DEFAULT NULL,
  `ExamCategory` int(11) DEFAULT NULL,
  `Score` varchar(30) DEFAULT NULL,
  `publish` varchar(1) NOT NULL DEFAULT '0',
  `createdby` int(11) NOT NULL,
  `createdon` date NOT NULL,
  `modifiedby` int(11) NOT NULL,
  `modifiedon` date NOT NULL,
  `publish_CA` int(11) NOT NULL DEFAULT '0',
  `RPT` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Score` (`Score`),
  KEY `RegNo` (`RegNo`),
  KEY `AYear` (`AYear`),
  KEY `CourseCode` (`CourseCode`),
  KEY `ExamCategory` (`ExamCategory`),
  KEY `Semester` (`Semester`)
) ENGINE=InnoDB



