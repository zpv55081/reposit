ALTER TABLE  0passing_competencies DROP FOREIGN KEY passing_competencies_FK;
ALTER TABLE  0passing_competencies ADD CONSTRAINT passing_competencies_FK FOREIGN KEY (competencies_id) REFERENCES  0competencies(id) ON DELETE RESTRICT ON UPDATE RESTRICT;
