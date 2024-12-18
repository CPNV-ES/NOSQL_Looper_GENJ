CREATE TABLE "users" (
  "id" SERIAL PRIMARY KEY,
  "username" text UNIQUE NOT NULL,
  "password" text NOT NULL,
  "role" integer NOT NULL DEFAULT 0 
);

CREATE TABLE "exercises" (
  "id" SERIAL PRIMARY KEY,
  "title" text NOT NULL,
  "status" integer NOT NULL DEFAULT 0
);

CREATE TABLE "fields" (
  "id" SERIAL PRIMARY KEY,
  "exercise_id" integer NOT NULL,
  "label" text NOT NULL,
  "kind" integer NOT NULL,
  "answer" text NOT NULL
);

CREATE TABLE "fulfillments" (
  "id" SERIAL PRIMARY KEY,
  "exercise_id" integer NOT NULL,
  "creation_date" timestamp NOT NULL DEFAULT now()
);

CREATE TABLE "fulfillments_data" (
  "id" SERIAL PRIMARY KEY,
  "fulfillment_id" integer NOT NULL,
  "field_id" integer NOT NULL,
  "body" text NOT NULL
);

ALTER TABLE "fields" ADD FOREIGN KEY ("exercise_id") REFERENCES "exercises" ("id") ON DELETE CASCADE;

ALTER TABLE "fulfillments" ADD FOREIGN KEY ("exercise_id") REFERENCES "exercises" ("id") ON DELETE CASCADE;

ALTER TABLE "fulfillments_data" ADD FOREIGN KEY ("fulfillment_id") REFERENCES "fulfillments" ("id") ON DELETE CASCADE;

ALTER TABLE "fulfillments_data" ADD FOREIGN KEY ("field_id") REFERENCES "fields" ("id") ON DELETE CASCADE;