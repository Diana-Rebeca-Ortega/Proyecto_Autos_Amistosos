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
                echo 'Limpiando contenedores anteriores y desplegando con Docker Compose...'
                bat 'docker rm -f pipeline_autos_amistosos-mysql_db-1 pipeline_autos_amistosos-app-1 || exit 0'
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