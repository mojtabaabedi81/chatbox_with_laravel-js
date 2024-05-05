<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Include TalkJS script -->
    <script>
        (function(t,a,l,k,j,s){
            s=a.createElement('script');s.async=1;s.src="https://cdn.talkjs.com/talk.js";a.head.appendChild(s)
            ;k=t.Promise;t.Talk={v:2,ready:{then:function(f){if(k)return new k(function(r,e){l.push([f,r,e])});l
                        .push([f])},catch:function(){return k&&new k()},c:l}};})(window,document,[]);
    </script>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

    <!-- Style for contact list -->
    <style>
        #contacts-list {
            overflow-y: auto;
            width: 300px;
            height: 500px;
            background-color: #ECECEC;
            border-radius:0.75rem;
            border:1px solid #D4D4D4;
            font-family: 'Inter';
        }
        #contacts-list h2{
            padding:20px;
        }
        .contact-container {
            height: 50px;
            display: flex;
            padding: 5px 0;
            cursor: pointer;
            border-bottom:1px solid #D4D4D4;
        }
        .contact-container:hover {
            background-color: #007DF9;
            color:#fff;
            font-weight: bold;
        }
        .contact-name {
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }
    </style>
</head>
<body>
<!-- Your HTML code -->
<div style="display: flex; justify-content: center; align-items: center;">
    <!-- container element in which TalkJS will display a chat UI -->
    <div id="talkjs-container" style="width: 40%; margin: 30px; height: 500px;">
        <i>Loading chat...</i>
    </div>
    <div id="contacts-list">
        <h2>Contacts</h2>
    </div>

    <!-- JavaScript code for TalkJS initialization -->
    <script type="text/javascript">
        // Retrieve other users data from server
        const otherUsers = @json($users);
        const currentUser = @json(auth()->user());

        // Wait for TalkJS to be ready
        Talk.ready.then(function () {
            // Create current user
            let me = new Talk.User({
                id: currentUser.id,
                name: currentUser.name,
            });

            // Initialize TalkJS session
            window.talkSession = new Talk.Session({
                appId: 't9dhlQMG', // Replace with your TalkJS App ID
                me: me,
            });

            // Create contacts list
            const contactsWrapper = document.getElementById('contacts-list');
            for (let user of otherUsers) {
                const username = user.name;

                const usernameDiv = document.createElement('div');
                usernameDiv.classList.add('contact-name');
                usernameDiv.innerText = username;

                const contactContainerDiv = document.createElement('div');
                contactContainerDiv.classList.add('contact-container');

                // Add click event to open chat
                contactContainerDiv.addEventListener('click', function () {
                    var inbox = talkSession.createInbox({ selected: user });
                    inbox.mount(document.getElementById("talkjs-container"));
                });

                contactContainerDiv.appendChild(usernameDiv);
                contactsWrapper.appendChild(contactContainerDiv);
            }

            // Create chatbox and conversations
            const chatbox = talkSession.createChatbox();
            chatbox.mount(document.getElementById('talkjs-container'));
            const conversations = otherUsers.map(function (user, index) {
                const otherUser = new Talk.User(user);

                conversation = talkSession.getOrCreateConversation(Talk.oneOnOneId(me, otherUser));

                conversation.setParticipant(me);
                conversation.setParticipant(otherUser);

                return conversation;
            });

            // Add event listeners to contact list elements
            let contactsListDivs = document.getElementsByClassName('contact-container');
            conversations.forEach(function (conversation, index) {
                contactsListDivs[index].addEventListener('click', function () {
                    chatbox.select(conversation);
                });
            });
        });
    </script>
    <a href="{{route('chat')}}">TalkJS Chat</a>
</div>
</body>
</html>
