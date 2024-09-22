import NotFound from "../pages/NotFound";
import AttendStatus from '../pages/AttendStatus';
import WorkStatus from "../pages/WorkStatus";
import MonthlySummery from "../pages/MonthlySummery";
import ShiftCreate from "../pages/ShiftCreate";
import WorkTotal from "../pages/WorkTotal";
import IndividualSummery from "../pages/IndividualSummery";
import Master from "../pages/Master";
import RegionMaster from "../pages/RegionMaster";
import EmployeeMaster from "../pages/EmployeeMaster";
import WorkingHoursMaster from "../pages/WorkingHoursMaster";
import HourlyMaster from "../pages/HourlyMaster";
import VacationMaster from "../pages/VacationMaster";
import Setting from "../pages/Setting";
import OfficeMaster from "../pages/OfficeMaster";
import Login from "../pages/Login";
import Stamp from '../pages/Stamp';
import BaseLayout from '../layout/BaseLayout';
import { Guards } from "../global/consts";

export default [
    {
        path: "/",
        component: BaseLayout,
        children: [
            {
                path: "",
                name: "stamp",
                components: { default: Stamp },
                meta: {
                    icon: "fas fa-clock",
                    anchor: "打刻",
                    menu: true,
                    guards: [
                        Guards.ADMIN,
                        Guards.REGION_MANAGER,
                        Guards.OFFICE_MANAGER,
                        Guards.USER_A,
                        Guards.USER_B
                    ]
                }
            },
            {
                path: "attendance-status",
                name: "attendance-status",
                components: { default: AttendStatus },
                meta: {
                    icon: "fas fa-file-alt",
                    anchor: "勤怠状況",
                    menu: true,
                    guards: [
                        Guards.ADMIN,
                        Guards.REGION_MANAGER,
                        Guards.OFFICE_MANAGER,
                        Guards.USER_A,
                        Guards.USER_B
                    ]
                }
            },
            {
                path: "work-status",
                name: "work-status",
                components: { default: WorkStatus },
                meta: {
                    icon: "fas fa-history",
                    anchor: "出勤状況",
                    menu: true,
                    guards: [
                        Guards.REGION_MANAGER,
                        Guards.OFFICE_MANAGER,
                    ]
                }
            },
            {
                path: "monthly-summary",
                name: "monthly-summary",
                components: { default: MonthlySummery },
                meta: {
                    icon: "fas fa-plus-square",
                    anchor: "月間集計",
                    menu: true,
                    guards: [
                        Guards.REGION_MANAGER,
                        Guards.OFFICE_MANAGER,
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "shift-create",
                name: "shift-create",
                components: { default: ShiftCreate },
                meta: {
                    icon: "fas fa-edit",
                    anchor: "シフト計画",
                    menu: true,
                    guards: [
                        Guards.REGION_MANAGER,
                        Guards.OFFICE_MANAGER,
                        Guards.USER_A,
                    ]
                }
            },
            {
                path: "work-total",
                name: "work-total",
                components: { default: WorkTotal },
                meta: {
                    icon: "fas fa-plus-square",
                    anchor: "勤務集計",
                    menu: true,
                    guards: [
                        Guards.ADMIN,
                        Guards.REGION_MANAGER,
                    ]
                }
            },
            {
                path: "individual-summary",
                name: "individual-summary",
                components: { default: IndividualSummery },
                meta: {
                    icon: "fas fa-plus-square",
                    anchor: "個別集計",
                    menu: false,
                    menuKey: 'work-total',
                    guards: [
                        Guards.REGION_MANAGER,
                        Guards.OFFICE_MANAGER,
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "master",
                name: "master",
                components: { default: Master },
                meta: {
                    icon: "fas fa-network-wired",
                    anchor: "マスタ管理",
                    menu: true,
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "setting",
                name: "setting",
                components: { default: Setting },
                meta: {
                    icon: "fas fa-cog",
                    anchor: "設定管理",
                    menu: true,
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "office-master",
                name: "office-master",
                components: { default: OfficeMaster },
                meta: {
                    icon: "",
                    anchor: "事業所マスタ",
                    menu: false,
                    menuKey: 'master',
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "region-master",
                name: "region-master",
                components: { default: RegionMaster },
                meta: {
                    icon: "",
                    anchor: "エリアマスタ",
                    menu: false,
                    menuKey: 'master',
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "employee-master",
                name: "employee-master",
                components: { default: EmployeeMaster },
                meta: {
                    icon: "",
                    anchor: "社員マスタ",
                    menu: false,
                    menuKey: 'master',
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "workinghours-master",
                name: "workinghours-master",
                components: { default: WorkingHoursMaster },
                meta: {
                    icon: "",
                    anchor: "時間帯マスタ",
                    menu: false,
                    menuKey: 'master',
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "hourly-master",
                name: "hourly-master",
                components: { default: HourlyMaster },
                meta: {
                    icon: "",
                    anchor: "時給テーブルマスタ",
                    menu: false,
                    menuKey: 'master',
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "vacation-master",
                name: "vacation-master",
                components: { default: VacationMaster },
                meta: {
                    icon: "",
                    anchor: "休暇マスタ",
                    menu: false,
                    menuKey: 'master',
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            },
            {
                path: "schedule-master",
                name: "schedule-master",
                components: { default: AttendStatus },
                meta: {
                    icon: "",
                    anchor: "所定労働時間マスタ",
                    menu: false,
                    guards: [
                        Guards.ADMIN,
                    ]
                }
            }
        ]
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        guards: [
            Guards.ADMIN,
            Guards.REGION_MANAGER,
            Guards.OFFICE_MANAGER,
            Guards.USER_A,
            Guards.USER_B
        ],
    },
    {
        path: "*",
        component: NotFound,
        guards: [
            Guards.ADMIN,
            Guards.REGION_MANAGER,
            Guards.OFFICE_MANAGER,
            Guards.USER_A,
            Guards.USER_B
        ],
    }
];
