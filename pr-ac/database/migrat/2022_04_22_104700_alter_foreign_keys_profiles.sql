ALTER TABLE  profiles DROP FOREIGN KEY profiles_FK;
ALTER TABLE  profiles ADD CONSTRAINT profiles_FK FOREIGN KEY (department_post_id) REFERENCES  department_post(id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE  profiles DROP FOREIGN KEY profiles_FK_1;
ALTER TABLE  profiles ADD CONSTRAINT profiles_FK_1 FOREIGN KEY (organizations_id) REFERENCES  organizations(id) ON DELETE RESTRICT ON UPDATE RESTRICT;
