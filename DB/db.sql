CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_UNIQUE` (`email` ASC))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `permission` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(40) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `permission_id` INT(5) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX (`user_id` ASC),
  INDEX (`permission_id` ASC),
  CONSTRAINT `fk_user_permission_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_permission_permission`
    FOREIGN KEY (`permission_id`)
    REFERENCES `permission` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


INSERT INTO permission (name) VALUES ('ADMIN'), ('users.create');
-- password: asdasd
INSERT INTO user (name, email, password) VALUES ('Admin', 'admin@admin.com', 'a8f5f167f44f4964e6c998dee827110c');