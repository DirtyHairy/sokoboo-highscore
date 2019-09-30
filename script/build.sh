#!/bin/bash

REPOSITORY="git@github.com:DirtyHairy/sokoboo-highscore.git"

die() {
    echo ERROR: $@
    exit 1
}

alternatives() {
    while test -n "$1"; do
        alt=`which $1`
        test -x "$alt" && break
    done

    echo $alt
}

READLINK=`alternatives greadlink readlink`
test -x "$READLINK" || die "readlink not available"

GIT=`alternatives git`
test -x "$GIT" || die "git not available"

COMPOSER=`alternatives composer`
test -x "$COMPOSER" || die "composer not available"

DATE=`alternatives date`
test -x "$DATE" || die "date not available"

TAR=`alternatives gtar tar`
test -x "$TAR" || "tar not available"

current_dir=`"$READLINK" $0`
current_dir=`dirname $0`

build_id=`"$DATE" "+%Y%m%d-%H%M%S"`
build_dir="$current_dir/../build/$build_id"

test -e "$build_dir" && die "$build_dir already exists"
mkdir -p "$build_dir"
cd "$build_dir"

"$GIT" clone "$REPOSITORY" .
"$COMPOSER" install

echo "BUILD_ID=$build_id" >> .env
"$COMPOSER" dump-env prod

cd ..
tar -cjf "build-$build_id.tar.bz2" --exclude .git "$build_id"

echo
echo build $build_id is ready
