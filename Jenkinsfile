pipeline {
    agent any

    stages {
        stage('Clonar Repositorio') {
            steps {
                git branch: 'main', url: 'https://github.com/Diana-Rebeca-Ortega/Proyecto_Autos_Amistosos.git'
            }
        }
        stage('Instalar Dependencias') {
            steps {
                sh 'npm install'
            }
        }
        stage('Ejecutar Pruebas Automatizadas') {
            steps {    sh 'npm test' }
        }
    }
    post {
        success {
            echo '¡El pipeline y las pruebas automatizadas se ejecutaron exitosamente!'
        }
        failure {
            echo 'El pipeline falló en alguna de las etapas.'
        }
    }
}