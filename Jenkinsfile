def app = "virtual"
def reg = "localhost:5000"
def img = "registry.sti.bayern.de/${app}"
def loc = "${reg}/${app}"
def oid = ""
def nid = ""

pipeline {
    agent any

    options {
        buildDiscarder(logRotator(numToKeepStr: '1'))
        disableConcurrentBuilds()
    }

    stages {
        stage("Build") {
            steps {
                script {
                    oid = sh(returnStdout: true, script: "sudo docker inspect --format='{{.Id}}' ${img} || true").trim()
                }

                sh "sudo docker build -t ${img} ."

                script {
                    nid = sh(returnStdout: true, script: "sudo docker inspect --format='{{.Id}}' ${img}").trim()
                }
            }
        }

        stage("Registry") {
            steps {
                sh "sudo docker tag ${img} ${loc}"

                withCredentials([usernamePassword(credentialsId: 'registry', passwordVariable: 'pass', usernameVariable: 'user')]) {
                    sh "sudo docker login -u ${user} -p ${pass} ${reg}"
                }

                sh "sudo docker push ${loc}"
                sh "sudo docker rmi ${loc}"
            }
        }

        stage("Deploy") {
            steps {
                withCredentials([usernamePassword(credentialsId: 'gogs', passwordVariable: 'pass', usernameVariable: 'user')]) {
                    sh "curl -o .env https://${user}:${pass}@gogs.sti.bayern.de/sti/admin/raw/master/.env"
                }

                sh "sudo docker-compose -p ${app} -f docker-compose.yml rm -sf"
                sh "sudo docker-compose -p ${app} -f docker-compose.yml up -d --force-recreate"
            }
        }

        stage("Clean") {
            steps {
                script {
                    if (oid && !oid.equals(nid)) {
                        sh "sudo docker rmi ${oid}"
                    }
                }
            }
        }
    }

    post {
        always {
            deleteDir()
            sh "sudo docker image prune -f"
        }
    }
}
