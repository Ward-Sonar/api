from troposphere import Join, Ref


def create_default_queue_name_variable(environment_parameter, uuid_parameter):
    return Join('-', [Ref(environment_parameter), Ref(uuid_parameter), 'default'])


def create_notifications_queue_name_variable(environment_parameter, uuid_parameter):
    return Join('-', [Ref(environment_parameter), Ref(uuid_parameter), 'notifications'])


def create_search_queue_name_variable(environment_parameter, uuid_parameter):
    return Join('-', [Ref(environment_parameter), Ref(uuid_parameter), 'search'])


def create_uploads_bucket_name_variable(environment_parameter, uuid_parameter):
    return Join('-', ['uploads', Ref(environment_parameter), Ref(uuid_parameter)])


def create_api_launch_template_name_variable(environment_parameter):
    return Join('-', ['api-launch-template', Ref(environment_parameter)])


def create_docker_repository_name_variable(environment_parameter, uuid_parameter):
    return Join('-', ['api', Ref(environment_parameter), Ref(uuid_parameter)])


def create_api_log_group_name_variable(environment_parameter):
    return Join('-', ['api', Ref(environment_parameter)])


def create_queue_worker_log_group_name_variable(environment_parameter):
    return Join('-', ['queue-worker', Ref(environment_parameter)])


def create_scheduler_log_group_name_variable(environment_parameter):
    return Join('-', ['scheduler', Ref(environment_parameter)])


def create_api_task_definition_family_variable(environment_parameter):
    return Join('-', ['api', Ref(environment_parameter)])


def create_queue_worker_task_definition_family_variable(environment_parameter):
    return Join('-', ['queue-worker', Ref(environment_parameter)])


def create_scheduler_task_definition_family_variable(environment_parameter):
    return Join('-', ['scheduler', Ref(environment_parameter)])


def create_api_user_name_variable(environment_parameter):
    return Join('-', ['api', Ref(environment_parameter)])


def create_ci_user_name_variable(environment_parameter):
    return Join('-', ['ci-api', Ref(environment_parameter)])


def create_database_access_ip_ranges_variable(environment_parameter):
    return [
        '64.18.0.0/20',
        '64.233.160.0/19',
        '66.102.0.0/20',
        '66.249.80.0/20',
        '72.14.192.0/18',
        '74.125.0.0/16',
        '108.177.8.0/21',
        '173.194.0.0/16',
        '207.126.144.0/20',
        '209.85.128.0/17',
        '216.58.192.0/19',
        '216.239.32.0/19'
    ]


def create_elasticsearch_domain_name_variable(environment_parameter):
    return Join('-', ['search', Ref(environment_parameter)])
