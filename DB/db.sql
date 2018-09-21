CREATE TABLE IF NOT EXISTS `user` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_UNIQUE` (`email` ASC))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `permission_id` INT(5) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_permission_user1_idx` (`user_id` ASC),
  INDEX `fk_user_permission_permission1_idx` (`permission_id` ASC),
  CONSTRAINT `fk_user_permission_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_permission_permission1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `permission` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(40) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


INSERT INTO permission (name) VALUES ('users.create');