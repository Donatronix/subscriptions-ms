#!/bin/bash -x
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color
#SOURCE="${BASH_SOURCE[0]}"
#cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && cd ../
git submodule init
git submodule update --remote
REVISION=`git rev-parse HEAD`
TIME_TAG=`date  +%Y-%m-%d-%H`
BRANCH=`git status |head -n 1|awk '{print $3}'`
DEPLOY_NAME=`basename $(git remote show -n origin | grep URL|head -1 | cut -d: -f2-)|awk -F '.' '{print $1}'`
DOCKER_ECR_REPO_URL="005279544259.dkr.ecr.us-west-2.amazonaws.com"

case "$1" in
build)
    echo "${GREEN}Start Build${NC}\n"
    echo "Deploy Name: $DEPLOY_NAME\nBranch: $BRANCH\nRev: $REVISION\n"
    docker build -f Dockerfile -t $DOCKER_ECR_REPO_URL/$DEPLOY_NAME:$BRANCH-$REVISION .
    ;;

push)
    echo "${GREEN}Push Docker IMAGE Build ${NC}\n"
    echo "Deploy Name: $DEPLOY_NAME\nBranch: $BRANCH\nRev: $REVISION\n"
    docker push  $DOCKER_ECR_REPO_URL/$DEPLOY_NAME:$BRANCH-$REVISION
    ;;
start)
    echo "${GREEN}Generate docker-compose${NC}\n"
    echo "Deploy Name: $DEPLOY_NAME\nBranch: $BRANCH\nRev: $REVISION"
    cat compose-tmpl.yaml | grep -v "#"  > docker-compose.yaml
    sed -i"" "s~{{DEPLOY_NAME}}~$DEPLOY_NAME~" docker-compose.yaml
    sed -i"" "s~{{DOCKER_IMAGE}}~$DOCKER_ECR_REPO_URL/$DEPLOY_NAME:$BRANCH-$REVISION~" docker-compose.yaml
    echo "Run docker-compose up "
    docker-compose stop
    docker-compose up -d
    ;;
up)
    echo "${GREEN}Generate docker-compose${NC}\n"
    echo "Deploy Name: $DEPLOY_NAME\nBranch: $BRANCH\nRev: $REVISION"
    cat compose-tmpl.yaml | grep -v "#"  > docker-compose.yaml
    sed -i"" "s~{{DEPLOY_NAME}}~$DEPLOY_NAME~" docker-compose.yaml
    sed -i"" "s~{{DOCKER_IMAGE}}~$DOCKER_ECR_REPO_URL/$DEPLOY_NAME:$BRANCH-$REVISION~" docker-compose.yaml
    echo "Run docker-compose up "
    docker-compose stop
    docker-compose up -d
    ;;
stop)
    echo "${GREEN}Stop docker-compose${NC}\n"
    docker-compose stop
    ;;
login)
    echo "${GREEN}Login to Elastic Container Registry${NC}\n"
    aws ecr get-login --region us-west-2 --no-include-email |sh
    ;;
rm)
    echo "${GREEN}Stop docker-compose${NC}\n"
    docker-compose rm -f
    ;;

*) echo "${RED}Command $1 is not implemented${NC}\n"
   ;;
esac
