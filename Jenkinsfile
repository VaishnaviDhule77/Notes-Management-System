stage('Clean Workspace') {
    steps {
        deleteDir()
    }
}
pipeline {
    agent any

    environment {
        DEPLOY_PATH = 'C:/xampp/htdocs/Student-Notes'
    }

    stages {

        stage('Clone Repository') {
            steps {
                git branch: 'main',
                    url: 'https://github.com/VaishnaviDhule77/Student-Notes-Management-System.git'
            }
        }

        stage('PHP Syntax Check') {
            steps {
                bat '''
                for %%f in (*.php) do php -l %%f
                '''
            }
        }

        stage('Deploy to Localhost') {
            steps {
                bat '''
                if not exist "%DEPLOY_PATH%" mkdir "%DEPLOY_PATH%"
                xcopy /E /Y /I * "%DEPLOY_PATH%"
                '''
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
