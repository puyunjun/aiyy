<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

// 为方便系统核心升级，二次开发中需要用到的公共函数请写在这个文件，不要去修改common.php文件




include_once VENDOR_PATH.'sts-server/aliyun-php-sdk-core/Config.php';
use Sts\Request\V20150401 as Sts;
function sts_service(){


    $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "LTAI51hYlhC2tcaI", "MK0jX7tz4GKlxXEDRiL2ojD4un7bBA");
    $client = new DefaultAcsClient($iClientProfile);

// 角色资源描述符，在RAM的控制台的资源详情页上可以获取
    $roleArn = "acs:ram::1457803921485409:role/aliyuntestappuser";

    /*  {
          "Statement": [
      {
          "Action": "sts:AssumeRole",
        "Effect": "Allow",
        "Resource": "*"
      }
    ],
    "Version": "1"
  }*/
// 在扮演角色(AssumeRole)时，可以附加一个授权策略，进一步限制角色的权限；
// 详情请参考《RAM使用指南》
// 此授权策略表示读取所有OSS的只读权限
    $policy=<<<POLICY

{
  "Statement": [
    {
      "Action": [
        "oss:GetObject",
        "oss:PutObject",
        "oss:DeleteObject",
        "oss:ListParts",
        "oss:AbortMultipartUpload",
        "oss:ListObjects"
      ],
      "Effect": "Allow",
      "Resource": ["acs:oss:*:*:puyunjun/*", "acs:oss:*:*:puyunjun"]
    }
  ],
  "Version": "1"
}
POLICY;

    $request = new Sts\AssumeRoleRequest();
// RoleSessionName即临时身份的会话名称，用于区分不同的临时身份
// 您可以使用您的客户的ID作为会话名称
    $request->setRoleSessionName("client_name");
    $request->setRoleArn($roleArn);
    $request->setPolicy($policy);
    $request->setDurationSeconds(3600);
    $response = $client->doAction($request);


    $rows = array();
    $body = $response->getBody();
    $content = json_decode($body);
    $rows['status'] = $response->getStatus();
    if ($response->getStatus() == 200)
    {
        $rows['AccessKeyId'] = $content->Credentials->AccessKeyId;
        $rows['AccessKeySecret'] = $content->Credentials->AccessKeySecret;
        $rows['Expiration'] = $content->Credentials->Expiration;
        $rows['SecurityToken'] = $content->Credentials->SecurityToken;
    }
    else
    {
        $rows['AccessKeyId'] = "";
        $rows['AccessKeySecret'] = "";
        $rows['Expiration'] = "";
        $rows['SecurityToken'] = "";
    }

    echo json_encode($rows);
    return;

}
