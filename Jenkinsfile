#!/usr/bin/env groovy

env.JOB_NAME_FORMATTED = env.JOB_NAME.replaceAll("%2F", "/")

pipeline {
    agent any

    stages {
        stage('Start') {
            steps {
                slackSend (color: 'warning', message: "JOB: Build ${env.BUILD_DISPLAY_NAME} started for ${env.JOB_NAME_FORMATTED} (<${env.BUILD_URL}|View>)")
            }
        }

        stage('Composer') {
            steps {
                sh 'composer install --no-interaction --no-ansi'
            }
        }

        stage('Linter') {
            steps {
                sh 'vendor/bin/parallel-lint --no-colors classes'
            }
        }

        stage('Tests') {
            steps {
                sh 'vendor/bin/phpunit || exit 0'
            }
        }

        stage('CodeSniffer') {
            steps {
                sh 'vendor/bin/phpcs --standard=psr2 --report=checkstyle --report-file=build/logs/phpcs.xml classes || exit 0'
            }
        }

        stage('Reports') {
            steps {
                junit 'build/logs/junit/*.xml'
                checkstyle pattern: 'build/logs/phpcs.xml'
                publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'build/coverage', reportFiles: 'index.html', reportName: 'PHPUnit Code Coverage', reportTitles: ''])
            }
        }
    }

    post {
        success {
            slackSend (color: 'good', message: "JOB: Build ${env.BUILD_DISPLAY_NAME} success for ${env.JOB_NAME_FORMATTED} (<${env.BUILD_URL}|View>)")
        }
        failure {
            slackSend (color: 'danger', message: "JOB: Build ${env.BUILD_DISPLAY_NAME} *failed* for ${env.JOB_NAME_FORMATTED} (<${env.BUILD_URL}|View>) (@channel)")
        }
        unstable {
            slackSend (color: 'warning', message: "JOB: Build ${env.BUILD_DISPLAY_NAME} *unstable* for ${env.JOB_NAME_FORMATTED} (<${env.BUILD_URL}|View>)")
        }
        aborted {
            slackSend (color: '#9fa1a3', message: "JOB: Build ${env.BUILD_DISPLAY_NAME} aborted for ${env.JOB_NAME_FORMATTED} (<${env.BUILD_URL}|View>)")
        }
    }
}
