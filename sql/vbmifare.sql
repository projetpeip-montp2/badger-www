CREATE SCHEMA vbMifare;

CREATE TABLE vbMifare.`BadgingInformations` ( 
	`Mifare`             CHAR( 8 ),
	`Date`               DATE,
	`Time`               TIME
 );

CREATE TABLE vbMifare.`Classrooms` ( 
	`Id_classroom`       SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Name`               TEXT,
	`Size`               SMALLINT UNSIGNED,
	CONSTRAINT pk_classrooms PRIMARY KEY ( `Id_classroom` )
 );

CREATE TABLE vbMifare.`Config` ( 
	`Name`               TEXT,
	`Value`              TEXT
 );

CREATE TABLE vbMifare.`MCQs` ( 
	`Department`         TEXT,
	`SchoolYear`         SMALLINT UNSIGNED,
	`Date`               DATE,
	`StartTime`          TIME,
	`EndTime`            TIME
 );

CREATE TABLE vbMifare.`Packages` ( 
	`Id_package`         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Capacity`           SMALLINT UNSIGNED,
	`Name_fr`            TEXT,
	`Name_en`            TEXT,
	`Description_fr`     TEXT,
	`Description_en`     TEXT,
	CONSTRAINT pk_packages PRIMARY KEY ( `Id_package` )
 );

CREATE TABLE vbMifare.`Questions` ( 
	`Id_questions`       SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Label_fr`           TEXT,
	`Label_en`           TEXT,
	`Status`             ENUM( 'Possible','Impossible','Obligatory' ) ,
	CONSTRAINT pk_questions PRIMARY KEY ( `Id_questions` )
 );

CREATE INDEX idx_questions ON vbMifare.`Questions` ( `Id_package` );

CREATE TABLE vbMifare.`Answers` ( 
	`Id_answer`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_question`        SMALLINT UNSIGNED,
	`Label_fr`           TEXT,
	`Label_en`           TEXT,
	`TrueOrFalse`        CHAR( 1 ),
	CONSTRAINT pk_answers PRIMARY KEY ( `Id_answer` )
 );

CREATE INDEX idx_answers ON vbMifare.`Answers` ( `Id_question` );

CREATE TABLE vbMifare.`Availabilities` ( 
	`Id_availability`    SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_classroom`       SMALLINT UNSIGNED,
	`Date`               DATE,
	`StartTime`          TIME,
	`EndTime`            TIME,
	CONSTRAINT pk_availabilities PRIMARY KEY ( `Id_availability` )
 );

CREATE INDEX idx_availabilities ON vbMifare.`Availabilities` ( `Id_classroom` );

CREATE TABLE vbMifare.`DocumentsOfPackages` ( 
	`Id_document`        SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Filename`           TEXT,
	CONSTRAINT pk_documentsofpackages PRIMARY KEY ( `Id_document` )
 );

CREATE INDEX idx_documentsofpackages ON vbMifare.`DocumentsOfPackages` ( `Id_package` );

CREATE TABLE vbMifare.`ImagesOfPackages` ( 
	`Id_image`           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Id_zip`             SMALLINT UNSIGNED,
	`Filename`           TEXT,
	CONSTRAINT pk_imagesofpackages PRIMARY KEY ( `Id_image` )
 );

CREATE INDEX idx_imagesofpackages ON vbMifare.`ImagesOfPackages` ( `Id_package` );

CREATE TABLE vbMifare.`Lectures` ( 
	`Id_lecture`         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Id_availability`    SMALLINT UNSIGNED,
	`Lecturer`           TEXT,
	`Name_fr`            TEXT,
	`Name_en`            TEXT,
	`Description_fr`     TEXT,
	`Description_en`     TEXT,
	`Date`               DATE,
	`StartTime`          TIME,
	`EndTime`            TIME,
	CONSTRAINT pk_lectures PRIMARY KEY ( `Id_lecture` )
 );

CREATE INDEX idx_lectures ON vbMifare.`Lectures` ( `Id_package` );

CREATE INDEX idx_lectures_0 ON vbMifare.`Lectures` ( `Id_availability` );

CREATE TABLE vbMifare.`Users` ( 
	`Id_user`            VARCHAR( 60 ),
	`MCQStatus`          ENUM( 'Visitor','CanTakeMCQ','Generated','Taken' ) ,
	`Mark`               FLOAT,
	CONSTRAINT pk_users_0 UNIQUE ( `Id_user` )
 );

CREATE TABLE vbMifare.`AnswersOfUsers` ( 
	`Id_user`            VARCHAR( 60 ),
	`Id_question`        SMALLINT UNSIGNED,
	`Id_answer`          SMALLINT UNSIGNED
 );

CREATE INDEX idx_answersofusers ON vbMifare.`AnswersOfUsers` ( `Id_user` );

CREATE INDEX idx_answersofusers_0 ON vbMifare.`AnswersOfUsers` ( `Id_answer` );

CREATE INDEX idx_answersofusers_1 ON vbMifare.`AnswersOfUsers` ( `Id_question` );

CREATE TABLE vbMifare.`DocumentsOfUsers` ( 
	`Id_package`         SMALLINT UNSIGNED,
	`Id_user`            VARCHAR( 60 ),
	`Filename`           TEXT
 );

CREATE INDEX idx_documentsofusers ON vbMifare.`DocumentsOfUsers` ( `Id_package` );

CREATE INDEX idx_documentsofusers_0 ON vbMifare.`DocumentsOfUsers` ( `Id_user` );

CREATE TABLE vbMifare.`QuestionsOfUsers` ( 
	`Id_user`            VARCHAR( 60 ),
	`Id_question`        SMALLINT UNSIGNED
 );

CREATE INDEX idx_questionsofusers ON vbMifare.`QuestionsOfUsers` ( `Id_user` );

CREATE INDEX idx_questionsofusers_0 ON vbMifare.`QuestionsOfUsers` ( `Id_question` );

CREATE TABLE vbMifare.`Registrations` ( 
	`Id_lecture`         SMALLINT UNSIGNED,
	`Id_package`         SMALLINT UNSIGNED,
	`Id_user`            VARCHAR( 60 ),
	`Status`             ENUM( 'Coming','Absent','Present' ) 
 );

CREATE INDEX idx_registrations ON vbMifare.`Registrations` ( `Id_lecture` );

CREATE INDEX idx_registrations_0 ON vbMifare.`Registrations` ( `Id_package` );

CREATE INDEX idx_registrations_1 ON vbMifare.`Registrations` ( `Id_user` );

ALTER TABLE vbMifare.`Answers` ADD CONSTRAINT fk_answers_questions FOREIGN KEY ( `Id_question` ) REFERENCES vbMifare.`Questions`( `Id_questions` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`AnswersOfUsers` ADD CONSTRAINT fk_answersofusers_users FOREIGN KEY ( `Id_user` ) REFERENCES vbMifare.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`AnswersOfUsers` ADD CONSTRAINT fk_answersofusers_answers FOREIGN KEY ( `Id_answer` ) REFERENCES vbMifare.`Answers`( `Id_answer` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`AnswersOfUsers` ADD CONSTRAINT fk_answersofusers_questions FOREIGN KEY ( `Id_question` ) REFERENCES vbMifare.`Questions`( `Id_questions` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`Availabilities` ADD CONSTRAINT fk_availabilities_classrooms FOREIGN KEY ( `Id_classroom` ) REFERENCES vbMifare.`Classrooms`( `Id_classroom` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`DocumentsOfPackages` ADD CONSTRAINT fk_documentsofpackages FOREIGN KEY ( `Id_package` ) REFERENCES vbMifare.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`DocumentsOfUsers` ADD CONSTRAINT fk_documentsofusers_packages FOREIGN KEY ( `Id_package` ) REFERENCES vbMifare.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`DocumentsOfUsers` ADD CONSTRAINT fk_documentsofusers_users FOREIGN KEY ( `Id_user` ) REFERENCES vbMifare.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`ImagesOfPackages` ADD CONSTRAINT fk_imagesofpackages_packages FOREIGN KEY ( `Id_package` ) REFERENCES vbMifare.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`Lectures` ADD CONSTRAINT fk_lectures_packages FOREIGN KEY ( `Id_package` ) REFERENCES vbMifare.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`Questions` ADD CONSTRAINT fk_questions_packages FOREIGN KEY ( `Id_package` ) REFERENCES vbMifare.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`QuestionsOfUsers` ADD CONSTRAINT fk_questionsofusers_users FOREIGN KEY ( `Id_user` ) REFERENCES vbMifare.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`QuestionsOfUsers` ADD CONSTRAINT fk_questionsofusers_questions FOREIGN KEY ( `Id_question` ) REFERENCES vbMifare.`Questions`( `Id_questions` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`Registrations` ADD CONSTRAINT fk_registrations_lectures FOREIGN KEY ( `Id_lecture` ) REFERENCES vbMifare.`Lectures`( `Id_lecture` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`Registrations` ADD CONSTRAINT fk_registrations_packages FOREIGN KEY ( `Id_package` ) REFERENCES vbMifare.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE vbMifare.`Registrations` ADD CONSTRAINT fk_registrations_users FOREIGN KEY ( `Id_user` ) REFERENCES vbMifare.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

-- TODO: Voir comment faire pour cette clés étrangère.
-- ALTER TABLE vbMifare.`Users` ADD CONSTRAINT fk_users_userspolytech FOREIGN KEY ( `Id_user` ) REFERENCES vbMifare.`UsersPolytech`( `Username` ) ON DELETE CASCADE ON UPDATE NO ACTION;




INSERT INTO vbMifare.`Config` (`Name`, `Value`) VALUES
('MCQMaxQuestions', '10'),
('canSubscribe', '1'),
('presentMark', '5'),
('adminsList', 'victor.hiairrassary;gregoire.guisez'),
('availablesLanguagesList', 'fr;en'),
('packageRegistrationsCount', '5'),
('registrationsDateLimit', '23-08-2012'),
('reportSizeLimitFrontend', '10000000'),
('documentSizeLimitBackend', '10000000'),
('zipFileSizeLimitBackend', '10000000');

