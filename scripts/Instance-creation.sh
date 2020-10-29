#!/bin/bash


##Variables

PROJECT_NAME="prueba-borrar7"

## Create a VPC
AWS_VPC_ID=$(aws ec2 create-vpc \
--cidr-block 10.0.0.0/16 \
--query 'Vpc.{VpcId:VpcId}' \
--output text)
AWS_VPC="$AWS_VPC_ID"

echo $AWS_VPC_ID;
## Enable DNS hostname for your VPC
aws ec2 modify-vpc-attribute \
--vpc-id $AWS_VPC_ID \
--enable-dns-hostnames "{\"Value\":true}"

## Create a public subnet
AWS_SUBNET_PUBLIC_ID=$(aws ec2 create-subnet \
--vpc-id $AWS_VPC_ID --cidr-block 10.0.0.0/16 \
--availability-zone eu-west-1a --query 'Subnet.{SubnetId:SubnetId}' \
--output text)
AWS_VPC="$AWS_VPC $AWS_SUBNET_PUBLIC_ID"

## Enable Auto-assign Public IP on Public Subnet
aws ec2 modify-subnet-attribute \
--subnet-id $AWS_SUBNET_PUBLIC_ID \
--map-public-ip-on-launch

echo $AWS_SUBNET_PUBLIC_ID;
## Create an Internet Gateway
AWS_INTERNET_GATEWAY_ID=$(aws ec2 create-internet-gateway \
--query 'InternetGateway.{InternetGatewayId:InternetGatewayId}' \
--output text)
AWS_VPC="$AWS_VPC $AWS_INTERNET_GATEWAY_ID"

## Attach Internet gateway to your VPC
aws ec2 attach-internet-gateway \
--vpc-id $AWS_VPC_ID \
--internet-gateway-id $AWS_INTERNET_GATEWAY_ID

## Create a route table
AWS_CUSTOM_ROUTE_TABLE_ID=$(aws ec2 create-route-table \
--vpc-id $AWS_VPC_ID \
--query 'RouteTable.{RouteTableId:RouteTableId}' \
--output text)
AWS_VPC="$AWS_VPC $AWS_CUSTOM_ROUTE_TABLE_ID"

## Create route to Internet Gateway
aws ec2 create-route \
--route-table-id $AWS_CUSTOM_ROUTE_TABLE_ID \
--destination-cidr-block 0.0.0.0/0 \
--gateway-id $AWS_INTERNET_GATEWAY_ID

## Associate the public subnet with route table
AWS_ROUTE_TABLE_ASSOID=$(aws ec2 associate-route-table \
--subnet-id $AWS_SUBNET_PUBLIC_ID \
--route-table-id $AWS_CUSTOM_ROUTE_TABLE_ID \
--output text | head -1)
AWS_VPC="$AWS_VPC $AWS_ROUTE_TABLE_ASSOID"

## Create a security group
aws ec2 create-security-group \
--vpc-id $AWS_VPC_ID \
--group-name $PROJECT_NAME"-securitygroup" \
--description 'My VPC non default security group'


## Get security group ID's
AWS_DEFAULT_SECURITY_GROUP_ID=$(aws ec2 describe-security-groups \
--filters "Name=vpc-id,Values=$AWS_VPC_ID" \
--query 'SecurityGroups[?GroupName == default].GroupId' \
--output text)
AWS_VPC="$AWS_VPC $AWS_DEFAULT_SECURITY_GROUP_ID"

AWS_CUSTOM_SECURITY_GROUP_ID=$(aws ec2 describe-security-groups --filters Name=group-name,Values=${PROJECT_NAME}-securitygroup Name=vpc-id,Values="$AWS_VPC_ID" --query "SecurityGroups[*].[GroupId]" --output text)

# AWS_CUSTOM_SECURITY_GROUP_ID=$(aws ec2 describe-security-groups \
# --filters "Name=vpc-id,Values=$AWS_VPC_ID" \
# --query 'SecurityGroups[?GroupName == "myvpcsecuritygroup"].GroupId' \
# --output text)


AWS_VPC="$AWS_VPC $AWS_CUSTOM_SECURITY_GROUP_ID"



## Create security group ingress rules
aws ec2 authorize-security-group-ingress \
--group-id $AWS_CUSTOM_SECURITY_GROUP_ID \
--ip-permissions '[{"IpProtocol": "tcp", "FromPort": 22, "ToPort": 22, "IpRanges": [{"CidrIp": "0.0.0.0/0", "Description": "Allow SSH"}]}]'

aws ec2 authorize-security-group-ingress \
--group-id $AWS_CUSTOM_SECURITY_GROUP_ID \
--ip-permissions '[{"IpProtocol": "tcp", "FromPort": 80, "ToPort": 80, "IpRanges": [{"CidrIp": "0.0.0.0/0", "Description": "Allow HTTP"}]}]'

aws ec2 authorize-security-group-ingress \
--group-id $AWS_CUSTOM_SECURITY_GROUP_ID \
--ip-permissions '[{"IpProtocol": "tcp", "FromPort": 80, "ToPort": 80, "Ipv6Ranges": [{"CidrIpv6": "::/0", "Description": "Allow HTTP"}]}]'

aws ec2 authorize-security-group-ingress \
--group-id $AWS_CUSTOM_SECURITY_GROUP_ID \
--ip-permissions '[{"IpProtocol": "tcp", "FromPort": 443, "ToPort": 443, "IpRanges": [{"CidrIp": "0.0.0.0/0", "Description": "Allow HTTPS"}]}]'

aws ec2 authorize-security-group-ingress \
--group-id $AWS_CUSTOM_SECURITY_GROUP_ID \
--ip-permissions '[{"IpProtocol": "tcp", "FromPort": 443, "ToPort": 443, "Ipv6Ranges": [{"CidrIpv6": "::/0", "Description": "Allow HTTPS"}]}]'

## Add a tag to the VPC
aws ec2 create-tags \
--resources $AWS_VPC_ID \
--tags "Key=Name,Value=${PROJECT_NAME}-vpc"

## Add a tag to public subnet
aws ec2 create-tags \
--resources $AWS_SUBNET_PUBLIC_ID \
--tags "Key=Name,Value=${PROJECT_NAME}-public-subnet"

## Add a tag to the Internet-Gateway
aws ec2 create-tags \
--resources $AWS_INTERNET_GATEWAY_ID \
--tags "Key=Name,Value=${PROJECT_NAME}-internet-gateway"

## Add a tag to the default route table
AWS_DEFAULT_ROUTE_TABLE_ID=$(aws ec2 describe-route-tables \
--filters "Name=vpc-id,Values=$AWS_VPC_ID" \
--query 'RouteTables[?Associations[0].Main != flase].RouteTableId' \
--output text)
AWS_VPC="$AWS_VPC $AWS_DEFAULT_ROUTE_TABLE_ID"

aws ec2 create-tags \
--resources $AWS_DEFAULT_ROUTE_TABLE_ID \
--tags "Key=Name,Value=${PROJECT_NAME}-default-route-table"

## Add a tag to the public route table
aws ec2 create-tags \
--resources $AWS_CUSTOM_ROUTE_TABLE_ID \
--tags "Key=Name,Value=${PROJECT_NAME}-public-route-table"

## Add a tags to security groups
aws ec2 create-tags \
--resources $AWS_CUSTOM_SECURITY_GROUP_ID \
--tags "Key=Name,Value=${PROJECT_NAME}-security-group"

# aws ec2 create-tags \
# --resources $AWS_DEFAULT_SECURITY_GROUP_ID \
# --tags "Key=Name,Value=myvpc-default-security-group"

aws ec2 create-key-pair --key-name ${PROJECT_NAME}-key --query "KeyMaterial" --output text > "/home/leandropretel/Escritorio/${PROJECT_NAME}-key.pem"

## Running instance

aws ec2 run-instances --image-id ami-0823c236601fef765 --count 1 --instance-type t3.nano --key-name ${PROJECT_NAME}-key --security-group-ids $AWS_CUSTOM_SECURITY_GROUP_ID --subnet-id $AWS_SUBNET_PUBLIC_ID --tag-specifications "ResourceType=instance,Tags=[{Key=Name,Value=${PROJECT_NAME}}]" --user-data "$(cat /home/leandropretel/Trabajo/Beebit/pruebasCICD/server_install.sh)" 


AWS_PUBLIC_ELASTIC_IP=$(aws ec2 allocate-address --query 'PublicIp' --output text)
AWS_INSTANCE_ID=$(aws ec2 describe-instances --filters Name=tag:Name,Values=${PROJECT_NAME} --output text --query 'Reservations[*].Instances[*].InstanceId')
echo $AWS_PUBLIC_ELASTIC_IP



aws ec2 create-tags --resources $AWS_INSTANCE_ID --tags "Key=${PROJECT_NAME}-deploy,Value=true"

## Creation of application
aws deploy create-application --application-name ${PROJECT_NAME}-app
aws deploy create-deployment-group --application-name ${PROJECT_NAME}-app --deployment-group-name ${PROJECT_NAME}-group --deployment-config-name CodeDeployDefault.OneAtATime --ec2-tag-filters Key=${PROJECT_NAME}-deploy,Type=KEY_AND_VALUE,Value=true --service-role-arn arn:aws:iam::061242299943:role/CodeDeployServiceRole

## Creation of s3

aws s3 mb s3://${PROJECT_NAME}-s3-bucket --region eu-west-1
sleep 30
aws ec2 associate-iam-instance-profile --instance-id $AWS_INSTANCE_ID --iam-instance-profile Name=DeployCI
aws ec2 associate-address --instance-id $AWS_INSTANCE_ID --public-ip $AWS_PUBLIC_ELASTIC_IP
