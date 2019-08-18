CREATE TABLE employee
(
    id int NOT NULL,
    name varchar(50) NOT NULL,
    surname varchar(50),
    user_id int,
    CONSTRAINT employee_pk PRIMARY KEY (id),
    CONSTRAINT fk_employee_user FOREIGN KEY (user_id) REFERENCES user(id)
);




CREATE TABLE machine
(
    id int NOT NULL,
    name varchar(180) NOT NULL,
    brand varchar(180),
    kms int,
    CONSTRAINT machine_pk PRIMARY KEY (id)
);


CREATE TABLE employee_rel_machine
(
    employee_id int NOT NULL,
    machine_id int NOT NULL,
    CONSTRAINT fk_employee FOREIGN KEY (employee_id) REFERENCES employee(id),
    CONSTRAINT fk_machine FOREIGN KEY (machine_id) REFERENCES machine(id)
);

create table user
(
    id                     int auto_increment primary key,
    username                  varchar(100) not null,
    email                  varchar(100) not null,
    password               varchar(200)  null,
    salt                   varchar(40)  null,
    roles                   longtext null,
    constraint UNIQ_8D93D649F85E0677
        unique (username)
);


create table site
(
    id                     int auto_increment primary key,
    name                  varchar(100) not null,
    is_active             tinyint(1)   not null,
    created_at             datetime     not null comment '(DC2Type:datetime_immutable)'
);

CREATE TABLE employee_rel_site
(
    employee_id int NOT NULL,
    site_id int NOT NULL,
    CONSTRAINT fk_employee_site_e FOREIGN KEY (employee_id) REFERENCES employee(id),
    CONSTRAINT fk_employee_site_s FOREIGN KEY (site_id) REFERENCES site(id)
);
