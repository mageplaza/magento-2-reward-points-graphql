# Magento 2 Reward Points GraphQL / PWA

[Mageplaza Reward Points for Magento 2](https://www.mageplaza.com/magento-2-reward-points-extension/) is a powerful loyalty program that converts buyers into customers, increases conversion rates, and boosts sales with a smart automatic reward system. 

The reward point labels can be customized easily from the admin backend. You can use eye-catching point labels such as coin, flower, heart, etc., to attract customers to your reward programs. This will arouse their interest in your program and shopping in your store. Spending sliders will be another factor to increase customer experience and joyfulness when shopping in your store. Instead of filling in the number of points spent, they can pull the slider, and the corresponding point will be subtracted. 

It’s flexible for online store owners to set up rewards levels based on earning and spending rules. Reward points can also be given off when customers share your products or content on social media. This will help your products or services spread across social media while increasing the interaction between your customers and your store. Reward for behaviors is also effective as customers can earn points by performing an action. This is a great incentive for them to continue being active in your store with some actions like subscribing, purchasing, reviewing, or rating. They can even earn points by referring to their friends, who can also receive points and get an appealing discount on the store’s products. 

Magento Reward Points enables you to offer customers a transparent and reasonable point spending system that allows them to purchase products on your store by points. It makes the shopping process funnier, and customers can see the real advantages of reward points they’ve earned. 

Customers' earning and spending points can be tracked easily via an advanced report supported by the Mageplaza Report extension. The store admin will know if any customer is missing out on their points to immediately give them incentive offers or reward points to earn their interest back. Importing and exporting the reward points transaction are also done quickly, saving a lot of time for the store admin. 

Another noticeable feature is that **Magento 2 Reward Points GraphQL is now a part of Mageplaza Reward Points extension that adds GraphQL features.** This supports PWA compatibility. With this extension, you can get and push data on the website with GraphQl.

## 1. How to install

Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-reward-points-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

**Note:** 
Magento 2 Reward Points GraphQL requires installing [Mageplaza Reward Points](https://www.mageplaza.com/magento-2-reward-points-extension/) in your Magento installation. 

## 2. How to use
To start working with Reward Points GraphQL in Magento, here are the requirements to follow: 
- Use Magento 2.3.x. Return your site to developer mode. 
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers). 
- Set **GraphQL endpoint** as `http://<magento2-3-server>/graphql` in the url box. Click on Set endpoint. (e.g., `http://develop.mageplaza.com/graphql/ce232/graphql`). 
- The queries and mutations that Mageplaza support helps to see details of Transaction, Invitation, etc., through customer query, view Spending Point information, create Invite Email, and more. You can find more details [here](https://documenter.getpostman.com/view/10589000/SzRyzpww?version=latest). 

## 3. Devdocs
- [Magento 2 Reward Points API & examples](https://documenter.getpostman.com/view/10589000/SzRyzpwz?version=latest)
- [Magento 2 Reward Points GraphQL & examples](https://documenter.getpostman.com/view/10589000/SzRyzpww?version=latest)

Click on Run in Postman to add these collections to your workspace quickly.

![Magento 2 blog graphql pwa](https://i.imgur.com/lhsXlUR.gif)

## 4. Contribute to this module 
Feel free to **Fork** and contribute to this module. 

You can create a pull request. We will consider to merge your changes in the main branch. 

## 5. Get support
- Feel free to [contact us](https://www.mageplaza.com/contact.html) if you have any question. We're willing to hear from you and resolve your problems. 
- If you find this post helpful, please give us a **Star** ![star](https://i.imgur.com/S8e0ctO.png)
