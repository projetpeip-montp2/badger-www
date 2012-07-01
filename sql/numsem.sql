CREATE TABLE numsem.`BadgingInformations` ( 
	`Mifare`             CHAR( 8 ),
	`Date`               DATE,
	`Time`               TIME
 );

CREATE TABLE numsem.`Classrooms` ( 
	`Id_classroom`       SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Name`               TEXT,
	`Size`               SMALLINT UNSIGNED,
	CONSTRAINT pk_classrooms PRIMARY KEY ( `Id_classroom` )
 );

CREATE TABLE numsem.`Config` ( 
	`Name`               TEXT,
	`Description`        TEXT,
	`Value`              TEXT
 );

CREATE TABLE numsem.`MCQs` ( 
	`Id_mcq`             SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Department`         TEXT,
	`SchoolYear`         SMALLINT UNSIGNED,
	`Name`               TEXT,
	`Password`           TEXT,
	`Date`               DATE,
	`StartTime`          TIME,
	`EndTime`            TIME,
	CONSTRAINT pk_mcqs PRIMARY KEY ( `Id_mcq` )
 );

CREATE TABLE numsem.`Packages` ( 
	`Id_package`         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Capacity`           SMALLINT UNSIGNED,
	`Name_fr`            TEXT,
	`Name_en`            TEXT,
	`Description_fr`     TEXT,
	`Description_en`     TEXT,
	CONSTRAINT pk_packages PRIMARY KEY ( `Id_package` )
 );

CREATE TABLE numsem.`Questions` ( 
	`Id_question`        SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Label_fr`           TEXT,
	`Label_en`           TEXT,
	`Status`             ENUM( 'Possible','Impossible','Obligatory' ) ,
	CONSTRAINT pk_questions PRIMARY KEY ( `Id_question` )
 );

CREATE INDEX idx_questions ON numsem.`Questions` ( `Id_package` );

CREATE TABLE numsem.`ReplicationLogs` ( 
	`Date`               DATE,
	`Time`               TIME,
	`StatusCode`         ENUM( 'Success','DepartmentRemoveError' ) ,
	`Comment`            TEXT
 );

CREATE TABLE numsem.`SpecificLogins` ( 
	`Id_login`           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`UsernameUM2`        VARCHAR( 60 ),
	`Username`           VARCHAR( 60 ),
	CONSTRAINT pk_specificlogins PRIMARY KEY ( `Id_login` )
 );

CREATE TABLE numsem.`UsersPolytech` ( 
	`Username`           VARCHAR( 60 ) NOT NULL,
	`Num_Etudiant`       CHAR( 8 ),
	`Mifare`             CHAR( 8 ),
	`Actif`              CHAR( 1 ),
	`VraiNom`            CHAR( 30 ),
	`VraiPrenom`         CHAR( 20 ),
	`Departement`        ENUM( 'INFO' ) ,
	`anApogee`           TINYINT,
	CONSTRAINT pk_users PRIMARY KEY ( `Username` )
 );

CREATE TABLE numsem.`Answers` ( 
	`Id_answer`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_question`        SMALLINT UNSIGNED,
	`Label_fr`           TEXT,
	`Label_en`           TEXT,
	`TrueOrFalse`        CHAR( 1 ),
	CONSTRAINT pk_answers PRIMARY KEY ( `Id_answer` )
 );

CREATE INDEX idx_answers ON numsem.`Answers` ( `Id_question` );

CREATE TABLE numsem.`ArchivesOfPackages` ( 
	`Id_archive`         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Filename`           TEXT,
	CONSTRAINT pk_zipofimages PRIMARY KEY ( `Id_archive` )
 );

CREATE INDEX idx_archivesofimages ON numsem.`ArchivesOfPackages` ( `Id_package` );

CREATE TABLE numsem.`Availabilities` ( 
	`Id_availability`    SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_classroom`       SMALLINT UNSIGNED,
	`Date`               DATE,
	`StartTime`          TIME,
	`EndTime`            TIME,
	CONSTRAINT pk_availabilities PRIMARY KEY ( `Id_availability` )
 );

CREATE INDEX idx_availabilities ON numsem.`Availabilities` ( `Id_classroom` );

CREATE TABLE numsem.`DocumentsOfPackages` ( 
	`Id_document`        SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_package`         SMALLINT UNSIGNED,
	`Filename`           TEXT,
	CONSTRAINT pk_documentsofpackages PRIMARY KEY ( `Id_document` )
 );

CREATE INDEX idx_documentsofpackages ON numsem.`DocumentsOfPackages` ( `Id_package` );

CREATE TABLE numsem.`HistoryMifare` ( 
	`Id_user`            VARCHAR( 60 ),
	`Mifare`             CHAR( 8 )
 );

CREATE INDEX idx_historymifare ON numsem.`HistoryMifare` ( `Id_user` );

CREATE TABLE numsem.`ImagesOfArchives` ( 
	`Id_image`           SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_archive`         SMALLINT UNSIGNED,
	`Filename`           TEXT,
	CONSTRAINT pk_imagesofpackages PRIMARY KEY ( `Id_image` )
 );

CREATE INDEX idx_imagesofpackages_0 ON numsem.`ImagesOfArchives` ( `Id_archive` );

CREATE TABLE numsem.`Lectures` ( 
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

CREATE INDEX idx_lectures ON numsem.`Lectures` ( `Id_package` );

CREATE INDEX idx_lectures_0 ON numsem.`Lectures` ( `Id_availability` );

CREATE TABLE numsem.`Users` ( 
	`Id_user`            VARCHAR( 60 ),
	`MCQStatus`          ENUM( 'Visitor','CanTakeMCQ','Generated','Taken' ) ,
	`MCQMark`            FLOAT,
	`PresentMark`        FLOAT,
	`GenerateTime`       TIME,
	CONSTRAINT pk_users_0 UNIQUE ( `Id_user` )
 );

CREATE TABLE numsem.`AnswersOfUsers` ( 
	`Id_user`            VARCHAR( 60 ),
	`Id_question`        SMALLINT UNSIGNED,
	`Id_answer`          SMALLINT UNSIGNED
 );

CREATE INDEX idx_answersofusers ON numsem.`AnswersOfUsers` ( `Id_user` );

CREATE INDEX idx_answersofusers_0 ON numsem.`AnswersOfUsers` ( `Id_answer` );

CREATE INDEX idx_answersofusers_1 ON numsem.`AnswersOfUsers` ( `Id_question` );

CREATE TABLE numsem.`QuestionsOfUsers` ( 
	`Id_user`            VARCHAR( 60 ),
	`Id_question`        SMALLINT UNSIGNED
 );

CREATE INDEX idx_questionsofusers ON numsem.`QuestionsOfUsers` ( `Id_user` );

CREATE INDEX idx_questionsofusers_0 ON numsem.`QuestionsOfUsers` ( `Id_question` );

CREATE TABLE numsem.`Registrations` ( 
	`Id_registration`    SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_lecture`         SMALLINT UNSIGNED,
	`Id_package`         SMALLINT UNSIGNED,
	`Id_user`            VARCHAR( 60 ),
	`Status`             ENUM( 'Coming','Absent','Present' ) ,
	CONSTRAINT pk_registrations PRIMARY KEY ( `Id_registration` )
 );

CREATE INDEX idx_registrations ON numsem.`Registrations` ( `Id_lecture` );

CREATE INDEX idx_registrations_0 ON numsem.`Registrations` ( `Id_package` );

CREATE INDEX idx_registrations_1 ON numsem.`Registrations` ( `Id_user` );

CREATE TABLE numsem.`DocumentsOfUsers` ( 
	`Id_document`        SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`Id_lecture`         SMALLINT UNSIGNED,
	`Id_user`            VARCHAR( 60 ),
	`Id_registration`    SMALLINT UNSIGNED,
	`Filename`           TEXT,
	CONSTRAINT pk_documentsofusers PRIMARY KEY ( `Id_document` )
 );

CREATE INDEX idx_documentsofusers ON numsem.`DocumentsOfUsers` ( `Id_lecture` );

CREATE INDEX idx_documentsofusers_0 ON numsem.`DocumentsOfUsers` ( `Id_user` );

CREATE INDEX idx_documentsofusers_1 ON numsem.`DocumentsOfUsers` ( `Id_registration` );

ALTER TABLE numsem.`Answers` ADD CONSTRAINT fk_answers_questions FOREIGN KEY ( `Id_question` ) REFERENCES numsem.`Questions`( `Id_question` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`AnswersOfUsers` ADD CONSTRAINT fk_answersofusers_users FOREIGN KEY ( `Id_user` ) REFERENCES numsem.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`AnswersOfUsers` ADD CONSTRAINT fk_answersofusers_answers FOREIGN KEY ( `Id_answer` ) REFERENCES numsem.`Answers`( `Id_answer` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`AnswersOfUsers` ADD CONSTRAINT fk_answersofusers_questions FOREIGN KEY ( `Id_question` ) REFERENCES numsem.`Questions`( `Id_question` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`ArchivesOfPackages` ADD CONSTRAINT fk_archivesofimages FOREIGN KEY ( `Id_package` ) REFERENCES numsem.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Availabilities` ADD CONSTRAINT fk_availabilities_classrooms FOREIGN KEY ( `Id_classroom` ) REFERENCES numsem.`Classrooms`( `Id_classroom` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`DocumentsOfPackages` ADD CONSTRAINT fk_documentsofpackages FOREIGN KEY ( `Id_package` ) REFERENCES numsem.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`DocumentsOfUsers` ADD CONSTRAINT fk_documentsofusers_users FOREIGN KEY ( `Id_user` ) REFERENCES numsem.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`DocumentsOfUsers` ADD CONSTRAINT fk_documentsofusers_lectures FOREIGN KEY ( `Id_lecture` ) REFERENCES numsem.`Lectures`( `Id_lecture` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`DocumentsOfUsers` ADD CONSTRAINT fk_documentsofusers FOREIGN KEY ( `Id_registration` ) REFERENCES numsem.`Registrations`( `Id_registration` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`HistoryMifare` ADD CONSTRAINT fk_historymifare_userspolytech FOREIGN KEY ( `Id_user` ) REFERENCES numsem.`UsersPolytech`( `Username` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`ImagesOfArchives` ADD CONSTRAINT fk_imagesofarchives FOREIGN KEY ( `Id_archive` ) REFERENCES numsem.`ArchivesOfPackages`( `Id_archive` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Lectures` ADD CONSTRAINT fk_lectures_packages FOREIGN KEY ( `Id_package` ) REFERENCES numsem.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Questions` ADD CONSTRAINT fk_questions_packages FOREIGN KEY ( `Id_package` ) REFERENCES numsem.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`QuestionsOfUsers` ADD CONSTRAINT fk_questionsofusers_users FOREIGN KEY ( `Id_user` ) REFERENCES numsem.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`QuestionsOfUsers` ADD CONSTRAINT fk_questionsofusers_questions FOREIGN KEY ( `Id_question` ) REFERENCES numsem.`Questions`( `Id_question` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Registrations` ADD CONSTRAINT fk_registrations_lectures FOREIGN KEY ( `Id_lecture` ) REFERENCES numsem.`Lectures`( `Id_lecture` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Registrations` ADD CONSTRAINT fk_registrations_packages FOREIGN KEY ( `Id_package` ) REFERENCES numsem.`Packages`( `Id_package` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Registrations` ADD CONSTRAINT fk_registrations_users FOREIGN KEY ( `Id_user` ) REFERENCES numsem.`Users`( `Id_user` ) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE numsem.`Users` ADD CONSTRAINT fk_users_userspolytech FOREIGN KEY ( `Id_user` ) REFERENCES numsem.`UsersPolytech`( `Username` ) ON DELETE CASCADE ON UPDATE NO ACTION;

INSERT INTO numsem.`UsersPolytech` (`Username`, `VraiNom`, `VraiPrenom`, `Departement`, `anApogee`) VALUES
('berry', 'Berry', 'Vincent', 'INFO', 0);

INSERT INTO numsem.`SpecificLogins` (`UsernameUM2`, `Username`) VALUES
('vincent.berry', 'berry');

INSERT INTO numsem.`Config` (`Name`, `Description`, `Value`) VALUES
('HomepageTextfr', 'Texte français de la page d\'accueil', 'Texte de page d\'accueil'),
('HomepageTexten', 'Texte anglais de la page d\'accueil', 'Homepage text'),
('UserGuideTextfr', 'Texte français du guide d\'utilisateur', 'Texte du guide d\'utilisateur'),
('UserGuideTexten', 'Texte anglais du guide d\'utilisateur', 'User guide text'),
('LegalNoticeTextfr', 'Texte français des mentions légales', 'Texte des mentions légales'),
('LegalNoticeTexten', 'Texte anglais des mentions légales', 'Legal notice text'),
('MCQMaxQuestions', 'Nombre de questions dans le QCM', '5'),
('packageRegistrationsCount', 'Nombre de packages auxquels les étudiants doivent s''inscrire', '5'),
('minRegistrationsPerPackage', 'Nombre minimum d''inscrits par package', '3'),
('mailAppendix', 'Portion finale de l''adresse mail des étudiants', '@polytech.univ-montp2.fr'),
('mailSender', 'Adresse mail expéditrice', 'no-reply@polytech.univ-montp2.fr'),
('canSubscribe', 'Accès aux inscriptions', '1'),
('canViewPlanning', 'Accès au planning', '1'),
('canHandleReports', 'Accès à l''upload de rapports', '1'),
('registrationsDateLimit', 'Date limite des inscriptions (indiquée aux étudiants)', '1970-01-01'),
('reportSizeLimitFrontend', 'Taille limite des rapports, en octets', '10000000'),
('documentSizeLimitBackend', 'Taille limite des documents, en octets', '10000000'),
('zipFileSizeLimitBackend', 'Taille limite des archives d''images, en octets', '10000000'),
('adminsList', 'Liste des admins, séparés par des points-virgules', 'victor.hiairrassary;gregoire.guisez;berry'),
('maxBadQuestionPoints', 'Nombre maximun de points enlévé par question', '3'),
('availablesLanguagesList', 'Liste des langues disponibles, séparées par des points-virgules', 'fr;en');
