#-- 2016 10 02
ALTER TABLE tbl_issuesmanual ADD Created_Date_And_Time DATETIME NOT NULL;

#-- 2016 02 12
ALTER TABLE tbl_items ADD Can_Be_Sold VARCHAR(10) NOT NULL DEFAULT 'no';
ALTER TABLE tbl_items ADD Can_Be_Stocked VARCHAR(10) NOT NULL DEFAULT 'no';

#--UPDATE tbl_items SET Can_Be_Stocked = 'yes' Where Classification != '';
UPDATE tbl_items SET Can_Be_Stocked = 'yes' where Classification <> '';

UPDATE tbl_items SET Can_Be_Stocked = 'no' where Classification = '';

UPDATE tbl_items SET Can_Be_Sold = 'yes' where Classification = '';

UPDATE tbl_items SET Can_Be_Sold = 'no' where Classification <> '';

UPDATE tbl_items SET Can_Be_Sold = 'yes' where Classification = 'Pharmaceuticals';

#--2016 02 15
CREATE TABLE tbl_stocktaking (
    Stock_Taking_ID int(11) NOT NULL,
    Stock_Taking_Status varchar(30) NOT NULL DEFAULT 'pending',
    Stock_Taking_Date date NOT NULL,
    Stock_Taking_Description varchar(200) DEFAULT NULL,
    Sub_Department_ID int(11) DEFAULT NULL,
    Employee_ID int(11) DEFAULT NULL,
    Created_Date date NOT NULL,
    Created_Date_And_Time datetime NOT NULL,
    Supervisor_ID int(11) DEFAULT NULL,
    Branch_ID int(11) DEFAULT NULL
);

ALTER TABLE tbl_stocktaking
    ADD PRIMARY KEY (Stock_Taking_ID),
    ADD KEY Employee_ID (Employee_ID),
    ADD KEY Supervisor_ID (Supervisor_ID),
    ADD KEY Sub_Department_ID (Sub_Department_ID),
    ADD KEY Branch_ID (Branch_ID);

ALTER TABLE tbl_stocktaking
    MODIFY Stock_Taking_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE tbl_stocktaking
    ADD CONSTRAINT tbl_stocktaking_ibfk_2 FOREIGN KEY (Employee_ID) REFERENCES tbl_employee (Employee_ID),
    ADD CONSTRAINT tbl_stocktaking_ibfk_3 FOREIGN KEY (Supervisor_ID) REFERENCES tbl_employee (Employee_ID),
    ADD CONSTRAINT tbl_stocktaking_ibfk_4 FOREIGN KEY (Sub_Department_ID) REFERENCES tbl_sub_department (Sub_Department_ID),
    ADD CONSTRAINT tbl_stocktaking_ibfk_5 FOREIGN KEY (Branch_ID) REFERENCES tbl_branches (Branch_ID);

CREATE TABLE tbl_stocktaking_items (
    Stock_Taking_Item_ID int(11) NOT NULL,
    Item_ID int(11) NOT NULL,
    Over_Quantity int(11) DEFAULT NULL,
    Under_Quantity int(11) DEFAULT NULL,
    Item_Remark varchar(200) DEFAULT NULL,
    Stock_Taking_ID int(11) NOT NULL
);

ALTER TABLE tbl_stocktaking_items
    ADD PRIMARY KEY (Stock_Taking_Item_ID),
    ADD KEY Item_ID (Item_ID),
    ADD KEY Stock_Taking_ID (Stock_Taking_ID);

ALTER TABLE tbl_stocktaking_items
    MODIFY Stock_Taking_Item_ID int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE tbl_stocktaking_items
    ADD CONSTRAINT tbl_stocktaking_items_ibfk_1 FOREIGN KEY (Stock_Taking_ID) REFERENCES tbl_stocktaking (Stock_Taking_ID),
    ADD CONSTRAINT tbl_stocktaking_items_ibfk_2 FOREIGN KEY (Item_ID) REFERENCES tbl_items (Item_ID);

#-- 2016 02 23
ALTER TABLE tbl_disposal ADD Previous_Disposal_Data TEXT NULL;
ALTER TABLE tbl_issuesmanual ADD Previous_Issue_Note_Manual_Data TEXT NULL;
ALTER TABLE tbl_stocktaking ADD Previous_Stock_Taking_Data TEXT NULL;

#-- 2016 02 29
ALTER TABLE tbl_purchase_order ADD Previous_Purchase_Order_Data TEXT NULL;

#-- 2016 03 10
ALTER TABLE tbl_return_inward ADD Previous_Return_Inward_Data TEXT NULL;
ALTER TABLE tbl_return_outward ADD Previous_Return_Outward_Data TEXT NULL;
ALTER TABLE tbl_requisition ADD Previous_Requisition_Data TEXT NULL;