-- MySQL Script generated by MySQL Workbench
-- Sat Apr 30 08:52:37 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema readme
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `readme` ;

-- -----------------------------------------------------
-- Schema readme
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `readme` DEFAULT CHARACTER SET utf8 ;
USE `readme` ;

-- -----------------------------------------------------
-- Table `readme`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `reg_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `email` VARCHAR(128) NOT NULL,
    `login` VARCHAR(128) NOT NULL,
    `user_pass` VARCHAR(128) NOT NULL,
    `avatar` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    UNIQUE INDEX `email_UNIQUE` (`email` ASC),
    UNIQUE INDEX `login_UNIQUE` (`login` ASC))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`types`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`types` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(128) NOT NULL,
    `class` VARCHAR(128) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`posts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `dt_add` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `title` VARCHAR(255) NOT NULL,
    `text` TEXT(3000) NULL DEFAULT NULL,
    `quote_author` VARCHAR(128) NULL DEFAULT NULL,
    `image` VARCHAR(255) NULL DEFAULT NULL,
    `video` VARCHAR(255) NULL DEFAULT NULL,
    `link` VARCHAR(255) NULL DEFAULT NULL,
    `views` INT NULL DEFAULT 0,
    `post_author` INT NOT NULL,
    `post_type` INT NOT NULL,
    `origin_post` INT NULL,
    `origin_author` INT NULL,
    `repost` INT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `post_title` (`title` ASC),
    INDEX `post_author_idx` (`post_author` ASC),
    INDEX `post_type_idx` (`post_type` ASC),
    CONSTRAINT `post_author`
    FOREIGN KEY (`post_author`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `post_type`
    FOREIGN KEY (`post_type`)
    REFERENCES `readme`.`types` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`comments` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `dt_add` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `comment` TEXT(2000) NOT NULL,
    `author` INT NOT NULL,
    `post` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `com_author_idx` (`author` ASC),
    INDEX `com_post_idx` (`post` ASC),
    CONSTRAINT `com_author`
    FOREIGN KEY (`author`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `com_post`
    FOREIGN KEY (`post`)
    REFERENCES `readme`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`likes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `dt_add` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `author` INT NOT NULL,
    `post` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `like_author_idx` (`author` ASC),
    INDEX `like_post_idx` (`post` ASC),
    CONSTRAINT `like_author`
    FOREIGN KEY (`author`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `like_post`
    FOREIGN KEY (`post`)
    REFERENCES `readme`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`subscribes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`subscribes` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `follower` INT NOT NULL,
    `host` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `subscribe_follow_idx` (`follower` ASC),
    INDEX `subscribe_host_idx` (`host` ASC),
    CONSTRAINT `subscribe_follow`
    FOREIGN KEY (`follower`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `subscribe_host`
    FOREIGN KEY (`host`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`message` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `dt_add` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `message` TEXT NOT NULL,
    `sender` INT NOT NULL,
    `receiver` INT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC),
    INDEX `com_author_idx` (`sender` ASC),
    INDEX `mes_receiver_idx` (`receiver` ASC),
    CONSTRAINT `mes_author`
    FOREIGN KEY (`sender`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `mes_receiver`
    FOREIGN KEY (`receiver`)
    REFERENCES `readme`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `readme`.`hashtags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`hashtags` (
   `id` INT NOT NULL AUTO_INCREMENT,
   `hashtag` VARCHAR(128) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC))
    ENGINE = InnoDB;

CREATE FULLTEXT INDEX hashtag_search ON hashtags(hashtag);


-- -----------------------------------------------------
-- Table `readme`.`hash_posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `readme`.`hash_posts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `hashtag` INT NOT NULL,
    `post` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `hash_idx` (`hashtag` ASC),
    INDEX `hash_post_idx` (`post` ASC),
    CONSTRAINT `hash`
    FOREIGN KEY (`hashtag`)
    REFERENCES `readme`.`hashtags` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `hash_post`
    FOREIGN KEY (`post`)
    REFERENCES `readme`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
