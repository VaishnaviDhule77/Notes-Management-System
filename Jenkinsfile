pipeline {
    agent any

    environment {
        PHP_PATH    = 'C:\\xampp\\php\\php.exe'
        DEPLOY_PATH = 'C:\\xampp\\htdocs\\Student-Notes'
    }

    stages {

        stage('Clean Workspace') {
            steps {
                deleteDir()
            }
        }

        stage('Checkout Code') {
            steps {
                git branch: 'main',
                    url: 'https://github.com/VaishnaviDhule77/Notes-Management-System.git'
            }
        }

        stage('PHP Syntax Check') {
            steps {
                bat """
                "%PHP_PATH%" -l library\\admin\\get_book.php
                """
            }
        }

        stage('Deploy to Localhost') {
            steps {
                bat """
                if not exist "%DEPLOY_PATH%" mkdir "%DEPLOY_PATH%"
                xcopy /E /Y /I * "%DEPLOY_PATH%"
                """
            }
        }
    }

    post {
        success {
            echo '✅ Deployment successful!'
        }
        failure {
            echo '❌ Pipeline failed!'
        }
    }
}
