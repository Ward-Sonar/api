from troposphere import Template as BaseTemplate


def create_template():
    template = BaseTemplate(
        'Create the infrastructure needed to run the API')
    template.add_version('2010-09-09')

    return template
