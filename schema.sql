CREATE TABLE "urls" (
    "guid" TEXT NOT NULL,
    "url" TEXT NOT NULL
);
CREATE TABLE "sources" (
    "guid" TEXT NOT NULL,
    "name" TEXT NOT NULL,
    "fbid" TEXT
);
CREATE TABLE "url_source" (
    "url" TEXT NOT NULL,
    "source" TEXT NOT NULL
);
