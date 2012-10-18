CREATE TABLE xi_filelib_file (id INT NOT NULL, folder_id INT NOT NULL, resource_id INT NOT NULL, fileprofile VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, filelink VARCHAR(255) DEFAULT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status INT NOT NULL, uuid VARCHAR(36) NOT NULL, versions TEXT NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_E860652454840E92 ON xi_filelib_file (filelink);
CREATE UNIQUE INDEX UNIQ_E8606524D17F50A6 ON xi_filelib_file (uuid);
CREATE INDEX IDX_E8606524162CB942 ON xi_filelib_file (folder_id);
CREATE INDEX IDX_E860652489329D25 ON xi_filelib_file (resource_id);
CREATE UNIQUE INDEX folderid_filename_unique ON xi_filelib_file (folder_id, filename);
COMMENT ON COLUMN xi_filelib_file.versions IS '(DC2Type:array)';
CREATE TABLE xi_filelib_folder (id INT NOT NULL, parent_id INT DEFAULT NULL, foldername VARCHAR(255) NOT NULL, folderurl VARCHAR(5000) NOT NULL, uuid VARCHAR(36) NOT NULL, PRIMARY KEY(id));
CREATE UNIQUE INDEX UNIQ_A5EA9E8BD17F50A6 ON xi_filelib_folder (uuid);
CREATE INDEX IDX_A5EA9E8B727ACA70 ON xi_filelib_folder (parent_id);
CREATE TABLE xi_filelib_resource (id INT NOT NULL, hash VARCHAR(255) NOT NULL, mimetype VARCHAR(255) NOT NULL, filesize INT NOT NULL, exclusive BOOLEAN NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, versions TEXT NOT NULL, PRIMARY KEY(id));
COMMENT ON COLUMN xi_filelib_resource.versions IS '(DC2Type:array)';
CREATE SEQUENCE xi_filelib_file_id_seq INCREMENT BY 1 MINVALUE 1 START 10;
CREATE SEQUENCE xi_filelib_folder_id_seq INCREMENT BY 1 MINVALUE 1 START 10;
CREATE SEQUENCE xi_filelib_resource_id_seq INCREMENT BY 1 MINVALUE 1 START 10;
ALTER TABLE xi_filelib_file ADD CONSTRAINT FK_E8606524162CB942 FOREIGN KEY (folder_id) REFERENCES xi_filelib_folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE xi_filelib_file ADD CONSTRAINT FK_E860652489329D25 FOREIGN KEY (resource_id) REFERENCES xi_filelib_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE xi_filelib_folder ADD CONSTRAINT FK_A5EA9E8B727ACA70 FOREIGN KEY (parent_id) REFERENCES xi_filelib_folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
