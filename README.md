# Magento 2 with Docker on Kubernetes
We've built a set of Dockerfiles en Kubernetes manifests for running Magento 2 on Kubernetes. This repository contains examples and instructions to get you started. 

## Before you begin: Bootstrap your Magento project ##
  - Setup your Magento project with composer. Please follow the instructions at [devdocs.magento.com](https://devdocs.magento.com/guides/v2.3/install-gde/composer.html)
  - In the root of your Magento project, create a folder 'deployment' and add all files from [https://github.com/cloudlets/magento2/blob/master/](our example directory) to it.
  - Add your credentials to the auth.json-file in the deployments directory. Auth.json contains the necessary credentials for pulling packages from Magento's composer repository.

## Step 1: Extending our base image.
Our Docker image [m2-web-php](https://hub.docker.com/r/cloudlets/m2-web-php) on Docker Hub will be your starting point. The image contains an Nginx webserver with php-fpm, optimized for use with Magento 2. When you're intending to run Magento 2 on Kubernetes, you would want to extend this image with your Magento 2 artifact. 
  - Have a look at [our example Dockerfile](https://github.com/cloudlets/magento2/blob/master/examples/Magento2-application/Dockerfile) that extends our base image with the Magento codebase.
    - The first FROM-statement, as its name *builder* indicates, is used to build the Magento artifact. Customize this part if you want to adjust the artifact to your project's needs.
    - The second FROM-statement will build the Docker image that will be run your Magento application. Customize this part to suit your needs.
