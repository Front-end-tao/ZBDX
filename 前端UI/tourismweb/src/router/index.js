import Vue from 'vue'
import Router from 'vue-router'
import BeforeLogin from '../view/BeforeLogin.vue'
import AfterLogin from '../view/AfterLogin.vue'

import BeforeLogin_Right_Login from '../view/BeforeLogin/RIghtContentLogin/Login'
import BeforeLogin_Right_ForgetPw from '../view/BeforeLogin/RIghtContentLogin/ForgetPw'
import BeforeLogin_Right_SignUp from '../view/BeforeLogin/RIghtContentLogin/SignUp'
import BeforeLogin_Right_Admin from '../view/BeforeLogin/RIghtContentLogin/Admin'
import AdminPage from '../view/AfterLogin/AdminPage'
import UserPage from '../view/AfterLogin/UserPage'

import MainContentData from '../view/AfterLogin/AdminLoginComponents/MainContent/MainContentData'
import MainContentLetter from '../view/AfterLogin/AdminLoginComponents/MainContent/MainContentLetter'
import MainContentHotel from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentHotel'
import MainContentMessage from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentMessage'
import MainContentTicket from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentTicket'
import MainContentTravel from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentTravel'
import MainContentOrders from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentOrders'

import MainContentTravelBeforeConfirm from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentTravelComponents/BeforeConfirm'
import MainContentTravelConfirming from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentTravelComponents/Confirming'
import MainContentTravelCreateOrder from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentTravelComponents/CreateOrder'
import MainContentTravelPayOrder from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentTravelComponents/PayOrder'

import MainContentHotelGetHotel from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentHotelComponents/GetHotel'
import MainContentHotelSingleHotel from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentHotelComponents/SingleHotel'
import MainContentHotelConfirmingHotel from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentHotelComponents/ConfirmingHotel'
import MainContentHotelBeforeCreateOrder from '../view/AfterLogin/UserLoginComponents/MainContent/MainContentHotelComponents/BeforeCreateOrder'
// 解决重复进入相同路由时报错
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err);
}
Vue.use(Router)


export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/beforelogin',
      component: BeforeLogin,
      children: [
        {
          path: '/beforelogin/login',
          component: BeforeLogin_Right_Login,
          name: 'BeforeLogin_Right_Login'
        },
        {
          path: '/beforelogin/forget',
          component: BeforeLogin_Right_ForgetPw,
          name: 'BeforeLogin_Right_ForgetPw'
        },
        {
          path: '/beforelogin/signup',
          component: BeforeLogin_Right_SignUp,
          name: 'BeforeLogin_Right_SignUp'
        },
        {
          path: '/beforelogin/admin',
          component: BeforeLogin_Right_Admin,
          name: 'BeforeLogin_Right_Admin'
        }
      ],
      redirect: '/beforelogin/login'
    },
    {
      path: '/afterlogin',
      component: AfterLogin,
      children: [
        {
          path: '/afterlogin/admin',
          component: AdminPage,
          children: [
            {
              path: '/afterlogin/admin/data',
              component: MainContentData
            },
            {
              path: '/afterlogin/admin/letter',
              component: MainContentLetter
            }
          ],
          redirect: '/afterlogin/admin/data'
        },
        {
          path: '/afterlogin/user',
          component: UserPage,
          children: [
            {
              path: '/afterlogin/user/travel',
              component: MainContentTravel,
              children: [
                {
                  path: '/afterlogin/user/travel/beforeconfirm',
                  component: MainContentTravelBeforeConfirm
                },
                {
                  path: '/afterlogin/user/travel/confirming/:id',
                  component: MainContentTravelConfirming
                },
                {
                  path: '/afterlogin/user/travel/createorder',
                  component: MainContentTravelCreateOrder
                },
                {
                  path: '/afterlogin/user/travel/payorder',
                  component: MainContentTravelPayOrder
                }
              ],
              redirect: '/afterlogin/user/travel/beforeconfirm'
            },
            {
              path: '/afterlogin/user/ticket',
              component: MainContentTicket
            },
            {
              path: '/afterlogin/user/hotel',
              component: MainContentHotel,
              children: [
                {
                  path: '/afterlogin/user/hotel/gethotel',
                  component: MainContentHotelGetHotel
                },
                {
                  path: '/afterlogin/user/hotel/singlehotel/:hotelid',
                  component: MainContentHotelSingleHotel
                },
                {
                  path: '/afterlogin/user/hotel/confirming/:hotelid',
                  component: MainContentHotelConfirmingHotel
                },
                {
                  path: '/afterlogin/user/hotel/beforecreateorder',
                  component: MainContentHotelBeforeCreateOrder
                }
              ],
              redirect: '/afterlogin/user/hotel/gethotel'
            },
            {
              path: '/afterlogin/user/message',
              component: MainContentMessage
            },
            {
              path: '/afterlogin/user/orders',
              component: MainContentOrders
            }
          ],
          redirect: '/afterlogin/user/travel'
        }
      ]
    },
    {
      path: '*',
      redirect: '/beforelogin'
    }
  ]
})
