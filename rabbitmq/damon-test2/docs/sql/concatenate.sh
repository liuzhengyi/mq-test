#! /bin/sh -
# concatenate resettable* into resetalltables.sql
# concatenate testdata* into testdataalltables.sql

cat resettable* > resetalltables.sql
cat testdata* > testdataalltables.sql
