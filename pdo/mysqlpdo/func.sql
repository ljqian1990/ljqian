delimiter //
CREATE FUNCTION `getChildList`(rootId INT) 
	RETURNS char(255) 
	BEGIN 
		DECLARE str char(255) ; 
		DECLARE cid char(255) ; 

		SET str = ''; 
		SET cid =cast(rootId as CHAR); 

		WHILE cid is not null DO 
			SET str= concat(str,',',cid); 
			SELECT group_concat(id) INTO cid FROM place where FIND_IN_SET(parent_id,cid)>0; 
		END WHILE; 
		RETURN str; 
	END 
//   