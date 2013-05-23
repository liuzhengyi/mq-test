#! /bin/sh -
# concatenate resettable* into resetalltables.sql
# concatenate testdata* into testdataalltables.sql

cat resettable_* > resetalltables.sql
cat testdata_* > testdataalltables.sql
