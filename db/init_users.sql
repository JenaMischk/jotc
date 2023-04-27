CREATE TABLE "users" (
    "id" serial NOT NULL,
    PRIMARY KEY ("id"),
    "email" character varying(320) NOT NULL,
    "first_name" character varying(30) NOT NULL,
    "last_name" character varying(30) NOT NULL,
    "birth_date" date NOT NULL
);