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
        stage('Análisis de Código (SonarQube)') {
            steps {
                echo 'Ejecutando escaneo seguro con SonarQube...'
                withCredentials([string(credentialsId: 'sonarkube', variable: 'SONAR_TOKEN')]) {
                    bat "sonar-scanner -Dsonar.host.url=http://localhost:9000 -Dsonar.token=${SONAR_TOKEN}"
                }
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