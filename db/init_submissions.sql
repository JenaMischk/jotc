CREATE TABLE "submissions" (
  "id" serial NOT NULL,
  PRIMARY KEY ("id"),
  "user_id" integer NOT NULL,
  "input" text NOT NULL,
  "output" text NOT NULL,
  "date" timestamp NOT NULL
);