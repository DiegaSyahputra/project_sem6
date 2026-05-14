pipeline {
    agent any

    stages {

        stage('Test Jenkins') {
            steps {
                sh 'echo "Hello Jenkins"'
            }
        }

        stage('Check Workspace') {
            steps {
                sh 'pwd'
                sh 'ls'
            }
        }

        stage('Check Docker') {
            steps {
                sh 'docker --version'
            }
        }

    }
}