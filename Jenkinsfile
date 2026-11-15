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

        stage('Despliegue Continuo') {
            steps {

                echo 'Iniciando el despliegue automático con Docker Compose...'
               bat 'docker-compose up -d --build'
            }
        }
    }

    post {
        success {
            echo '¡El pipeline, las pruebas y el despliegue continuo se ejecutaron exitosamente!'
        }
        failure {
            echo 'El pipeline falló en alguna de las etapas.'
        }
    }
}