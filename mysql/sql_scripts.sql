CREATE TABLE employee
(
    id int auto_increment primary key,
    name varchar(50) NOT NULL,
    surname varchar(50),
    user_id int,
    CONSTRAINT employee_pk PRIMARY KEY (id),
    CONSTRAINT fk_employee_user FOREIGN KEY (user_id) REFERENCES user(id)
);




CREATE TABLE machine
(
    id int auto_increment primary key,
    name varchar(180) NOT NULL,
    brand varchar(180),
    kms int
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
    brand                  varchar(100) not null
);

create table material
(
    id                     int auto_increment primary key,
    name                  varchar(100) not null,
    price                   double not null
);

CREATE TABLE employee_rel_site
(
    employee_id int NOT NULL,
    site_id int NOT NULL,
    CONSTRAINT fk_employee_site_e FOREIGN KEY (employee_id) REFERENCES employee(id),
    CONSTRAINT fk_employee_site_s FOREIGN KEY (site_id) REFERENCES site(id)
);

create table ticket
(
    id                      int auto_increment primary key,
    employee_id             int not null,
    machine_id              int not null,
    site_id                 int not null,
    material_id                 int not null,
    hours                   int not null,
    num_travels             float not null,
    date                    date null,
    constraint UNIQ_8D93D649F85E0677
        unique (employee_id,machine_id,site_id,date),
    CONSTRAINT fk_ticket_employee FOREIGN KEY (employee_id) REFERENCES employee(id),
    CONSTRAINT fk_ticket_machine FOREIGN KEY (machine_id) REFERENCES machine(id),
    CONSTRAINT fk_ticket_site FOREIGN KEY (site_id) REFERENCES site(id),
    CONSTRAINT fk_ticket_material FOREIGN KEY (material_id) REFERENCES material(id)
);
