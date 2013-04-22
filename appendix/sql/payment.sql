
DROP TABLE IF EXISTS `pay_plugin`;
CREATE TABLE `pay_plugin`
(
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `description` text COMMENT '描述',
  `logo` varchar(255) default NULL COMMENT 'logo',
  `file_path` varchar(255) default NULL COMMENT '接口文件路径',
  `version` varchar(255) default NULL COMMENT '版本号',
  `visibility` tinyint(1) NOT NULL default '0' COMMENT '是否显示:0为隐藏,1为显示',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付插件表';

INSERT INTO `pay_plugin` VALUES (3, '八佰付在线支付', '支持币种：人民币，美元，韩元', '/payments/logos/pay_enets.gif', 'enets', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (9, '天天付在线支付（汇付天下）', '汇付天下（ www.chinapnr.com）是中国最具创新支付公司，注册资本过亿。', '/payments/logos/pay_chinapnr.gif', 'chinapnr', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (10, '招商银行', '中国第一家由企业创办的商业银行。', '/payments/logos/pay_cmbc.gif', 'cmbc', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (15, 'eNETS Payment esb_payments', '支持币种：美元, 新加坡元', '/payments/logos/pay_enets.gif', 'enets', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (16, 'EPAY网上支付', '支持币种：新台币', '/payments/logos/pay_epay.gif', 'epay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (20, '和讯在线支付', '支持币种：人民币', '/payments/logos/pay_homeway.gif', 'homeway', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (21, '广州银联网付通', '广州银联网络支付有限公司是银联体系内专业从事银行卡跨行网上支付、公共支付技术服务的高新技术企业。', '/payments/logos/pay_hyl.gif', 'hyl', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (22, '中国工商银行[1.0.0.3版]', '中国工商银行网上银行B2C支付网关可以使用在windows主机和linux主机，请在申请工行网关接口时申请1.0.0.3版。', '/payments/logos/pay_icbc.gif', 'icbc', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (23, 'IEPAY', '支持币种：新台币', '/payments/logos/pay_iepay.gif', 'iepay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (24, 'IPAY在线支付', '支持币种：人民币', '/payments/logos/pay_ipay.gif', 'ipay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (26, 'MOBILE88', '支持币种：马元', '/payments/logos/pay_mobile88.gif', 'mobile88', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (28, 'NOCHEX在线支付', '支持币种：英镑', '/payments/logos/pay_nochek.gif', 'nochek', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (30, 'NPS网上支付－内卡', '支持币种：人民币', '/payments/logos/pay_nps.gif', 'nps', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (31, 'NPS网上支付－外卡', '支持币种：人民币, 港币, 美元, 欧元', '/payments/logos/pay_nps_out.gif', 'nps_out', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (36, 'PayPal贝宝', '全球最大的在线支付平台，同时也是目前全球贸易网上支付标准.', '/payments/logos/pay_paypal.gif', 'paypal_cn', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (37, '首信易在线支付', '支持币种：人民币, 美元，是中国首家实现跨银行跨地域提供多种银行卡在线交易的网上支付服务平台', '/payments/logos/pay_shouxin.gif', 'shouxin', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (38, '我付了储值卡支付(OK卡等)', '我付了储值卡支付网关支持了如百联OK卡等在内的国内主流预付费卡（消费储值卡）的支付。', '/payments/logos/pay_skypay.gif', 'skypay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (40, 'SMSe在线支付', '支持币种：人民币，新台币', '/payments/logos/pay_smilepay.gif', 'smilepay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (42, '台湾里网上支付', '支持币种：新台币', '/payments/logos/pay_twv.gif', 'twv', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (43, '网汇通在线支付', '中国率先提供互联网现金汇款、支付的服务提供商， 提供“网汇通”业务的数据处理和经营。', '/payments/logos/pay_udpay.gif', 'udpay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (44, '银生支付', '支持币种：人民币', '/payments/logos/pay_unspay.gif', 'unspay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (47, '易宝支付 (在线支付接口)', '首批通过国家信息安全系统认证、获得企业信用等级AAA级证书、注册资本1亿元。', '/payments/logos/pay_yeepay.gif', 'yeepay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (50, '康辉商融彩虹平台', '康辉商融彩虹平台', '/payments/logos/pay_iris.gif', 'iris', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (17, 'Google Checkout', '支持币种：美元、欧元、英磅、马元', '/payments/logos/pay_epay.gif', 'epay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (32, '线下支付', '您可以通过现金付款或银行转帐的方式进行收款，如：中国银行  开户人：李白 帐号：4200 3234 2234 1234', '/payments/logos/pay_offline.gif', 'offline', '0.6', 1);
INSERT INTO `pay_plugin` VALUES (46, '网银在线支付（内卡）', '网银在线是中国领先的电子支付解决方案提供商之一。', '/payments/logos/pay_wangjin.gif', 'wangjin', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (2, '2CHECKOUT', '支持币种：澳元, 加拿大元, 欧元, 英磅, 港币, 日元, 韩元, 新加坡元, 美元', '/payments/logos/pay_2checkout.gif', '2checkout', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (11, '云网在线支付', '北京云网无限网络技术有限公司成立于1999年12月，是国内首家实现在线实时交易的电子商务公司。', '/payments/logos/pay_cncard.gif', 'cncard', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (12, '预存款支付', '预存款是客户在您网站上的虚拟资金帐户。', '/payments/logos/pay_deposit.gif', 'balance', '0.6', 1);
INSERT INTO `pay_plugin` VALUES (45, '网银在线支付（外卡）', '网银在线支付（外卡），网银网上支付是独立的安全支付平台。', '/payments/logos/pay_wangjin_out.gif', 'wangjin_out', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (8, '上海银联电子支付ChinaPay', '银联电子支付服务有限公司（ChinaPay）主要从事以互联网等新兴渠道为基础的网上支付。', '/payments/logos/pay_chinapay.gif', 'chinapay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (34, 'PayDollar', '领先的世界级电子付款及解决方案和技术供应商；支持币种：人民币、港币、美元、新加坡元、日元、新台币、澳元、欧元、英磅、加拿大元', '/payments/logos/pay_paydollar.gif', 'paydollar', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (41, '腾讯财付通[担保交易]', '财付通担保交易，由财付通做担保，买家确认才付款。 <a style="color:blue" href="http://union.tenpay.com/mch/mch_register_1.shtml?sp_suggestuser=2289480\r\n" target="_blank" >申请财付通担保账户</a>', '/payments/logos/pay_tenpaytrad.gif', 'tenpaytrad', '0.6', 1);
INSERT INTO `pay_plugin` VALUES (19, '运筹宝', '上海运筹宝电子商务有限公司（运筹宝Haipay ）是亚洲领先的在线支付服务提供商。', '/payments/logos/pay_google.gif', 'google', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (27, 'MONEYBOOKERS', '支持币种：澳元、加拿大元、欧元、英磅、港币、日元、韩元、新台币、新加坡元、美元', '/payments/logos/pay_moneybookers.gif', 'moneybookers', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (39, '腾讯财付通[即时到账]', '费率最低至<span style="color: #FF0000;font-weight: bold;">0.61%</span>，并赠送价值千元企业QQ。     <a style="color:blue" href="http://www.shopex.cn/pay/tenpay/" target="_blank">买套餐，送企业QQ</a>    <a style="color:blue" href="http://union.tenpay.com/mch/mch_register.shtml?sp_suggestuser=1202822001" target="_blank">中小商家签约入口</a>', '/payments/logos/pay_tenpay.gif', 'tenpay', '0.6', 1);
INSERT INTO `pay_plugin` VALUES (4, '快钱网上支付', '快钱是国内领先的独立第三方支付企业，旨在为各类企业及个人提供安全、便捷和保密的支付清算与账务服务。', '/payments/logos/pay_99bill.gif', '99bill', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (6, '支付宝[担保交易]', ' <a style="color:blue" href="https://www.alipay.com/himalayas/practicality_customer.htm?customer_external_id=C433530444855584111X&market_type=from_agent_contract&pro_codes=61F99645EC0DC4380ADE569DD132AD7A" target="_blank">立即申请</a>', '/payments/logos/pay_alipaytrad.gif', 'alipaytrad', '0.6', 1);
INSERT INTO `pay_plugin` VALUES (49, '中国移动手机支付', '仅对企业用户开放，年末超低费率<span style="color: #FF0000;font-weight: bold;">0.3%</span>签约，精准营销覆盖6.7亿用户   <a style="color:blue" href="http://www.shopex.cn/yidong/yidong.html" target="_blank">企业用户立即申请</a>\r\n', '/payments/logos/pay_cmpay.gif', 'cmpay', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (7, '支付宝[即时到帐]', '支付宝即时到帐，付款后立即到账，无预付/年费，单笔费率阶梯最低<span style="color: #FF0000;font-weight: bold;">0.7%</span>，无流量限制。  <a style="color:blue" href="https://www.alipay.com/himalayas/practicality_customer.htm?customer_external_id=C433530444855584111X&market_type=from_agent_contract&pro_codes=61F99645EC0DC4380ADE569DD132AD7A" target="_blank">立即申请</a>', '/payments/logos/pay_alipay.gif', 'alipay', '0.6', 1);
INSERT INTO `pay_plugin` VALUES (25, '环讯IPS网上支付3.0', '上海环迅电子商务有限公司（以下简称：环迅支付 ）成立于2000年，是国内最早的支付公司之一。', '/payments/logos/pay_ips3.gif', 'ips3', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (35, 'PayPal（外卡）', 'PayPal 是全球最大的在线支付平台，同时也是目前全球贸易网上支付标准。', '/payments/logos/pay_paypal.gif', 'paypal', '0.6', 0);
INSERT INTO `pay_plugin` VALUES (48, '拉卡拉支付', '不用网银也能支付！支持所有银行卡，刷卡付款。     <a style="color:blue" href="http://www.shopex.cn/pay/lakala.html" target="_blank">让网上交易覆盖网下3000万用户</a>', '/payments/logos/pay_lakala.gif', 'lakala', '0.6', 0);
