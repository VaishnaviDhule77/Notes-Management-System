node {
    stage('Clean Workspace') {
        deleteDir()
    }

    stage('Checkout Code') {
        git branch: 'main',
            url: 'https://github.com/VaishnaviDhule77/Notes-Management-System.git'
    }

    stage('PHP Syntax Check') {
        bat 'php -l library\\admin\\get_book.php'
    }

    stage('Deploy to Localhost') {
        echo 'Deploying to localhost...'
        // add copy commands later if needed
    }
}
