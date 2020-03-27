/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : cms01

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 27/03/2020 13:14:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for db_admin
-- ----------------------------
DROP TABLE IF EXISTS `db_admin`;
CREATE TABLE `db_admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `admin_password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `role_id` int(11) NULL DEFAULT 0 COMMENT '角色ID',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '0关闭，1开启',
  `mark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin
-- ----------------------------
INSERT INTO `db_admin` VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 'admin');
INSERT INTO `db_admin` VALUES (3, 'liao', 'e10adc3949ba59abbe56e057f20f883e', 2, 1, 'liao mark');

-- ----------------------------
-- Table structure for db_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_menu`;
CREATE TABLE `db_admin_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '菜单名称',
  `mark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  `acl` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '控制器名',
  `pid` int(11) NULL DEFAULT 0 COMMENT '父类',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1启用0关闭',
  `icon` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '图标',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序数字越大越靠前',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台菜单' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin_menu
-- ----------------------------
INSERT INTO `db_admin_menu` VALUES (1, '系统配置', '系统配置22', 'admin/adminlist', 0, 1, '&#xe62e;', 0);
INSERT INTO `db_admin_menu` VALUES (3, '管理员列表', '', 'admin/adminlist', 1, 1, '', 0);
INSERT INTO `db_admin_menu` VALUES (4, '角色列表', '', 'admin/rolelist', 1, 1, '', 0);
INSERT INTO `db_admin_menu` VALUES (5, '菜单列表', '', 'admin/menulist', 1, 1, '', 0);
INSERT INTO `db_admin_menu` VALUES (6, '会员管理', '', 'user/userlist', 0, 1, '', 0);
INSERT INTO `db_admin_menu` VALUES (7, '订单管理', '', 'order/orderlist', 0, 1, '', 0);
INSERT INTO `db_admin_menu` VALUES (9, '会员列表', '', '#', 6, 1, '', 0);

-- ----------------------------
-- Table structure for db_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_role`;
CREATE TABLE `db_admin_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `mark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  `aclList` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '权限列表',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of db_admin_role
-- ----------------------------
INSERT INTO `db_admin_role` VALUES (1, '超级管理员', '超级管理员', 'all');
INSERT INTO `db_admin_role` VALUES (2, '运营管理', '运营管理', 'admin/adminlist,admin/adminadd,admin/adminedit,admin/admindel,admin/menulist,admin/menuadd,admin/menuedit,admin/menudel');

SET FOREIGN_KEY_CHECKS = 1;
