myMenu.expandAll();
origApper = getCurrentFolder();

guider.createGuider({
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(1);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "The admin panel allows you to edit school settings & policies.",
      id: "1app",
      next: "2app",
      overlay: true,
      title: "This is the school admin panel."
    }).show();


    guider.createGuider({
      attachTo: "#xoar1",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(2);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "The dashboad shows the latest updates from ClassConnect and will soon display information / statistics about your school.",
      id: "2app",
      next: "3app",
      position: 7,
      title: "Main dashboard.",
      width: 400
    });


    guider.createGuider({
      attachTo: "#xoar2",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(5);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "This page allows you to edit school contact information including address, phone number, and more.",
      id: "3app",
      next: "5app",
      position: 7,
      title: "Your school's information.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar5",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(6);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "This page allows you to change your school color and logo.",
      id: "5app",
      next: "6app",
      position: 7,
      title: "Your school's logo & color.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar6",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(7);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "This page allows you to change your school's vanity URL. When students and teachers access your school vanity URL, they are greeted with your school logo and color instead of the ClassConnect defaults. (ex: http://yourschool.classconnect.com)",
      id: "6app",
      next: "7app",
      position: 7,
      title: "Your school's vanity URL.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar7",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(8);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "This page displays the number of days remaining in your ClasConnect premium subscription. Premium subscriptions give schools dedicated support, more storage, and the ability to integrate with existing SIS and SSO systems.",
      id: "7app",
      next: "8app",
      position: 7,
      title: "School subscription status.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar8",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(9);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "Search & edit students, teachers and administrators in your school.",
      id: "8app",
      next: "9app",
      position: 7,
      title: "Manage your users.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar9",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(10);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "Set strict user policies for your users. These include policies for account creation, communication, and applications.",
      id: "9app",
      next: "10app",
      position: 7,
      title: "Manage user policies.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar10",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(12);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "LDAP / SSO functionality is available to beta subscribers only. Contact ClassConnect sales & support to learn more.",
      id: "10app",
      next: "12app",
      position: 7,
      title: "LDAP / SSO settings.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar12",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(13);myMenu.expandAll(); guider.next();\">Next</a><a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "Set grading periods for your school. These allow you to keep track of all classes on ClassConnect during a grading period.",
      id: "12app",
      next: "13app",
      position: 3,
      title: "Manage school grading periods.",
      width: 400
    });

    guider.createGuider({
      attachTo: "#xoar13",
      buttons: [],
      buttonCustomHTML: "<a class=\"guider_button\" style=\"float:right\" onClick=\"swapPage(origApper);guider.hideAll();\">Close</a>",
      description: "Edit application policies for all classes in this school.",
      id: "13app",
      position: 3,
      title: "Manage class policies.",
      width: 400
    });
