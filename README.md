# Magento 2 with Docker on Kubernetes
We've built a set of Dockerfiles en Kubernetes manifests for running Magento 2 on Kubernetes. This repository contains examples and instructions to get you started. 

## Before you begin: Bootstrap your Magento project ##
  - Setup your Magento project with composer. Please follow the instructions at [devdocs.magento.com](https://devdocs.magento.com/guides/v2.3/install-gde/composer.html)
  - In the root of your Magento project, create a folder 'deployment'.
  - Add [auth.json](link) the the deployment folder. Auth.json contains the necessary credentials for pulling packages from Magento's composer repository.

## Step 1: Our base image
Our Docker image [m2-web-php](https://hub.docker.com/r/cloudlets/m2-web-php) on Docker Hub will be your starting point. The image contains an Nginx webserver with php-fpm, optimized for use with Magento 2. When you're intending to run Magento 2 on Kubernetes, you would want to extend this image with your Magento 2 artifact. 
  - example Dockerfile that extends our base image with the Magento codebase.
