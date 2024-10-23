
# Terraform config

terraform {
    required_providers {
        aws = {
            source  = "hashicorp/aws"
            version = "~> 5.18.0"
        }
    }
    backend "s3" {
        bucket  = "fidelify-terraform"
        key     = "fidelify-api-staging.tfstate"
        region  = "us-east-1"
        encrypt = true
    }
}

# AWS config

provider "aws" {
    region                   = "${var.region}"
    shared_credentials_files = ["~/.aws/credentials"]
}

# Environment

### IAM

resource "aws_iam_role" "fidelify-api-iam-role" {
    name = "${var.project}-role"
    assume_role_policy = jsonencode({
        Version = "2012-10-17",
        Statement = [
            {
                Action = "sts:AssumeRole",
                Effect = "Allow",
                Principal = {
                    Service = "ec2.amazonaws.com"
                }
            }
        ]
    })
}

resource "aws_iam_instance_profile" "fidelify-api-iam-instance-profile" {
    name = "${var.project}-instance-profile"
    role = aws_iam_role.fidelify-api-iam-role.name
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-ssm" {
    policy_arn = "arn:aws:iam::aws:policy/AmazonSSMManagedInstanceCore"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-ec2" {
    policy_arn = "arn:aws:iam::aws:policy/AmazonEC2ReadOnlyAccess"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-cw1" {
    policy_arn = "arn:aws:iam::aws:policy/CloudWatchFullAccess"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-cw2" {
    policy_arn = "arn:aws:iam::aws:policy/CloudWatchFullAccessV2"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

resource "aws_iam_user" "fidelify-api-iam-user" {
    name = "${var.project}-user"
}

### ECR

resource "aws_ecr_repository" "fidelify-api-ecr-repository" {
    name = "${var.project}"
}

resource "aws_iam_policy" "fidelify-api-iam-policy-ecr" {
    name   = "${var.project}-ecr-policy"
    policy = jsonencode({
        "Version": "2012-10-17",
        "Statement": [
            {
                "Effect": "Allow",
                "Action": [
                    "ecr:BatchGetImage",
                    "ecr:BatchCheckLayerAvailability",
                    "ecr:CompleteLayerUpload",
                    "ecr:DescribeImages",
                    "ecr:DescribeRepositories",
                    "ecr:GetDownloadUrlForLayer",
                    "ecr:InitiateLayerUpload",
                    "ecr:ListImages",
                    "ecr:PutImage",
                    "ecr:UploadLayerPart"
                ],
                "Resource": "${aws_ecr_repository.fidelify-api-ecr-repository.arn}"
            },
            {
                "Effect": "Allow",
                "Action": "ecr:GetAuthorizationToken",
                "Resource": "*"
            }
        ]
    })
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-ecr" {
    policy_arn = "${aws_iam_policy.fidelify-api-iam-policy-ecr.arn}"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

resource "aws_iam_user_policy_attachment" "fidelify-api-iam-user-policy-attachment-ecr" {
    policy_arn = "${aws_iam_policy.fidelify-api-iam-policy-ecr.arn}"
    user       = "${aws_iam_user.fidelify-api-iam-user.name}"
}

### EBS

resource "aws_ebs_volume" "fidelify-api-ebs-volume" {
    availability_zone = "${var.region}a"
    size              = 30
    type              = "gp2"
}

resource "aws_iam_policy" "fidelify-api-iam-policy-ebs-volume" {
    name   = "${var.project}-ebs-volume-policy"
    policy = jsonencode({
        "Version": "2012-10-17",
        "Statement": [
            {
                "Effect": "Allow",
                "Action": "ec2:AttachVolume",
                "Resource": [
                    "${aws_ebs_volume.fidelify-api-ebs-volume.arn}",
                    "arn:aws:ec2:${var.region}:${var.account}:instance/*"
                ]
            }
        ]
    })
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-ebs" {
    policy_arn = "${aws_iam_policy.fidelify-api-iam-policy-ebs-volume.arn}"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

### S3

resource "aws_iam_policy" "fidelify-api-iam-policy-s3" {
    name   = "${var.project}-s3-policy"
    policy = jsonencode({
        "Version": "2012-10-17",
        "Statement": [
            {
                "Effect": "Allow",
                "Action": "s3:GetObject",
                "Resource": [
                    "arn:aws:s3:::fidelify-terraform/${var.project}.env"
                ]
            }
        ]
    })
}

resource "aws_iam_role_policy_attachment" "fidelify-api-iam-role-policy-attachment-s3" {
    policy_arn = "${aws_iam_policy.fidelify-api-iam-policy-s3.arn}"
    role       = "${aws_iam_role.fidelify-api-iam-role.id}"
}

resource "aws_iam_user_policy_attachment" "fidelify-api-iam-user-policy-attachment-s3" {
    policy_arn = "${aws_iam_policy.fidelify-api-iam-policy-s3.arn}"
    user       = "${aws_iam_user.fidelify-api-iam-user.name}"
}

### EC2

resource "aws_security_group" "fidelify-api-security-group" {
    name        = "${var.project}-security-group"
    description = "${var.project}-security-group"
    vpc_id      = "${var.vpc}"

    egress {
        from_port   = 0
        to_port     = 0
        protocol    = "-1"
        cidr_blocks = ["0.0.0.0/0"]
    }
}

resource "local_file" "fidelify-api-launch-provision" {
    filename = format("%s/%s/%s", "${path.module}", "templates", "${var.project}-launch-provision.sh")

    content = templatefile("templates/launch-provision.tmpl", {
        region = "${var.region}"
        project = "${var.project}"
        bucket = "${var.bucket}"
        volume = "${aws_ebs_volume.fidelify-api-ebs-volume.id}"
        ecr = "${aws_ecr_repository.fidelify-api-ecr-repository.repository_url}"
    })
}

resource "aws_launch_template" "fidelify-api-launch-template" {
    name          = "${var.project}-launch-template"
    image_id      = "${var.image}"
    instance_type = "${var.instance}"
    key_name      = "${var.project}-key"

    user_data = filebase64("${path.module}/templates/${var.project}-launch-provision.sh")

    network_interfaces {
        subnet_id                   = "${var.subnet}"
        security_groups             = [aws_security_group.fidelify-api-security-group.id]
        associate_public_ip_address = true
    }

    iam_instance_profile {
        name = aws_iam_instance_profile.fidelify-api-iam-instance-profile.name
    }

    block_device_mappings {
        device_name = "/dev/sda1"

        ebs {
            volume_size = 8
            volume_type = "gp2"
        }
    }

    metadata_options {
        http_endpoint = "enabled"
        http_tokens   = "required"
    }
}

resource "aws_autoscaling_group" "fidelify-api-autoscaling-group" {
    name                      = "${var.project}-autoscaling-group"
    desired_capacity          = 0
    max_size                  = 0
    min_size                  = 0
    health_check_grace_period = 300
    vpc_zone_identifier       = ["${var.subnet}"]
    capacity_rebalance        = true

    mixed_instances_policy {
        launch_template {
            launch_template_specification {
                launch_template_id = aws_launch_template.fidelify-api-launch-template.id
                version = aws_launch_template.fidelify-api-launch-template.latest_version
            }
            override {
                instance_type     = "t3a.micro"
            }
            override {
                instance_type     = "t3.micro"
            }
        }

        instances_distribution {
            on_demand_allocation_strategy            = "prioritized"
            on_demand_base_capacity                  = 0
            on_demand_percentage_above_base_capacity = 0
            spot_allocation_strategy                 = "price-capacity-optimized"
        }
    }

    tag {
        key                 = "Name"
        value               = "${var.project}"
        propagate_at_launch = true
    }

    lifecycle {
        ignore_changes = [desired_capacity, max_size, min_size]
    }
}

resource "aws_iam_policy" "fidelify-api-iam-policy-asg" {
    name   = "${var.project}-autoscaling-group-policy"
    policy = jsonencode({
        "Version": "2012-10-17",
        "Statement": [
            {
                "Effect": "Allow",
                "Action": "autoscaling:UpdateAutoScalingGroup",
                "Resource": "${aws_autoscaling_group.fidelify-api-autoscaling-group.arn}"
            }
        ]
    })
}

resource "aws_iam_user_policy_attachment" "fidelify-api-iam-user-policy-attachment-asg" {
    policy_arn = "${aws_iam_policy.fidelify-api-iam-policy-asg.arn}"
    user       = "${var.project}-user"
}
