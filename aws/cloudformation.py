import uuid
from template import create_template
from parameters import create_uuid_parameter, create_environment_parameter, create_certificate_arn_parameter, create_vpc_parameter, \
    create_subnets_parameter, create_database_name_parameter, create_database_username_parameter, create_database_password_parameter, create_database_class_parameter, \
    create_database_allocated_storage_parameter, \
    create_api_instance_class_parameter, create_api_instance_count_parameter, create_api_task_count_parameter
from variables import create_uploads_bucket_name_variable, create_api_launch_template_name_variable, \
    create_docker_repository_name_variable, create_api_log_group_name_variable, create_api_task_definition_family_variable, \
    create_api_user_name_variable, create_ci_user_name_variable, create_database_name_variable, create_database_username_variable
from resources import create_load_balancer_security_group_resource, create_api_security_group_resource, \
    create_database_security_group_resource, create_database_subnet_group_resource, \
    create_database_resource, create_uploads_bucket_resource, \
    create_ecs_cluster_role_resource, create_ec2_instance_profile_resource, create_ecs_cluster_resource, \
    create_launch_template_resource, create_docker_repository_resource, create_api_log_group_resource, \
    create_api_task_definition_resource, \
    create_load_balancer_resource, \
    create_api_tarcreate_group_resource, create_load_balancer_listener_resource, create_ecs_service_role_resource, \
    create_api_service_resource, \
    create_autoscaling_group_resource, create_api_user_resource, create_ci_user_resource

from outputs import create_database_name_output, create_database_username_output, create_database_host_output, \
    create_database_port_output, create_load_balancer_domain_output, \
    create_docker_repository_uri_output, create_docker_cluster_name_output

# UUID.
uuid = str(uuid.uuid4())

# Template.
template = create_template()

# Parameters.
uuid_parameter = create_uuid_parameter(template, uuid)
environment_parameter = create_environment_parameter(template)
certificate_arn_parameter = create_certificate_arn_parameter(template)
vpc_parameter = create_vpc_parameter(template)
subnets_parameter = create_subnets_parameter(template)
database_name_parameter = create_database_name_parameter(template)
database_username_parameter = create_database_username_parameter(template)
database_password_parameter = create_database_password_parameter(template)
database_class_parameter = create_database_class_parameter(template)
database_allocated_storage_parameter = create_database_allocated_storage_parameter(
    template)
api_instance_class_parameter = create_api_instance_class_parameter(template)
api_instance_count_parameter = create_api_instance_count_parameter(template)
api_task_count_parameter = create_api_task_count_parameter(template)

# Variables.
uploads_bucket_name_variable = create_uploads_bucket_name_variable(
    environment_parameter, uuid_parameter)
api_launch_template_name_variable = create_api_launch_template_name_variable(
    environment_parameter)
docker_repository_name_variable = create_docker_repository_name_variable(
    environment_parameter, uuid_parameter)
api_log_group_name_variable = create_api_log_group_name_variable(
    environment_parameter)
api_task_definition_family_variable = create_api_task_definition_family_variable(
    environment_parameter)
api_user_name_variable = create_api_user_name_variable(environment_parameter)
ci_user_name_variable = create_ci_user_name_variable(environment_parameter)
database_name_variable = create_database_name_variable()
database_username_variable = create_database_username_variable()

# Resources.
load_balancer_security_group_resource = create_load_balancer_security_group_resource(
    template)
api_security_group_resource = create_api_security_group_resource(
    template, load_balancer_security_group_resource)
database_security_group_resource = create_database_security_group_resource(
    template, api_security_group_resource)
database_subnet_group_resource = create_database_subnet_group_resource(
    template, subnets_parameter)
database_resource = create_database_resource(template, database_name_variable, database_allocated_storage_parameter,
                                             database_class_parameter, database_username_variable,
                                             database_password_parameter, database_security_group_resource,
                                             database_subnet_group_resource)
uploads_bucket_resource = create_uploads_bucket_resource(
    template, uploads_bucket_name_variable)
ecs_cluster_role_resource = create_ecs_cluster_role_resource(template)
ec2_instance_profile_resource = create_ec2_instance_profile_resource(
    template, ecs_cluster_role_resource)
ecs_cluster_resource = create_ecs_cluster_resource(template)
launch_template_resource = create_launch_template_resource(template, api_launch_template_name_variable,
                                                           api_instance_class_parameter, ec2_instance_profile_resource,
                                                           api_security_group_resource, ecs_cluster_resource)
docker_repository_resource = create_docker_repository_resource(
    template, docker_repository_name_variable)
api_log_group_resource = create_api_log_group_resource(
    template, api_log_group_name_variable)
api_task_definition_resource = create_api_task_definition_resource(template, api_task_definition_family_variable,
                                                                   docker_repository_resource, api_log_group_resource)
load_balancer_resource = create_load_balancer_resource(
    template, load_balancer_security_group_resource, subnets_parameter)
api_tarcreate_group_resource = create_api_tarcreate_group_resource(
    template, vpc_parameter, load_balancer_resource)
load_balancer_listener_resource = create_load_balancer_listener_resource(template, load_balancer_resource,
                                                                         api_tarcreate_group_resource,
                                                                         certificate_arn_parameter)
ecs_service_role_resource = create_ecs_service_role_resource(template)
api_service_resource = create_api_service_resource(template, ecs_cluster_resource, api_task_definition_resource,
                                                   api_task_count_parameter, api_tarcreate_group_resource,
                                                   ecs_service_role_resource, load_balancer_listener_resource)
autoscaling_group_resource = create_autoscaling_group_resource(template, api_instance_count_parameter,
                                                               launch_template_resource)
api_user_resource = create_api_user_resource(
    template, api_user_name_variable, uploads_bucket_resource)
ci_user_resource = create_ci_user_resource(template, ci_user_name_variable)

# Outputs.
create_database_name_output(template, database_username_variable)
create_database_username_output(template, database_username_variable)
create_database_host_output(template, database_resource)
create_database_port_output(template, database_resource)
create_load_balancer_domain_output(template, load_balancer_resource)
create_docker_repository_uri_output(template, docker_repository_resource)
create_docker_cluster_name_output(template, ecs_cluster_resource)

# Print the generated template in JSON.
print(template.to_json())
