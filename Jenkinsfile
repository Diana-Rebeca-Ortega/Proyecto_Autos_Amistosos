pipeline {
    agent any

    stages {
        stage('Instalar Dependencias') {
            steps {
                bat 'npm install'
            }
        }

        stage('Ejecutar Pruebas Automatizadas') {
            steps {
                bat 'npm test'
            }
        }
    }

    post {
        success {
            echo '¡El pipeline y las pruebas automatizadas con Jest se ejecutaron exitosamente!'
        }
        failure {
            echo 'El pipeline falló en alguna de las etapas.'
        }
    }
}