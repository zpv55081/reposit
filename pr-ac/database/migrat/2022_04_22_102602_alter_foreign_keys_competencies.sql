ALTER TABLE competencies DROP FOREIGN KEY competencies_FK;
ALTER TABLE competencies ADD CONSTRAINT competencies_FK FOREIGN KEY (post_id) REFERENCES dep_po(id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE competencies DROP FOREIGN KEY competencies_FK_1;
ALTER TABLE competencies ADD CONSTRAINT competencies_FK_1 FOREIGN KEY (ability_id) REFERENCES abilities(id) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE competencies DROP FOREIGN KEY competencies_FK_2;
ALTER TABLE competencies ADD CONSTRAINT competencies_FK_2 FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE RESTRICT ON UPDATE RESTRICT;