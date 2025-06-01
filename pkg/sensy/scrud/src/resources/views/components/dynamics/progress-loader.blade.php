<div>

    <style>
        .bar {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 4px;
            /* background: #acece6; */
            overflow: hidden;
        }

        .bar div:before {
            content: "";
            position: absolute;
            top: 0px;
            left: 0px;
            bottom: 0px;
            /* background: hsl(58, 100%, 55%); */
            background: #04680e;
            animation: box-1 2100ms cubic-bezier(0.65, 0.81, 0.73, 0.4) infinite;
        }

        .bar div:after {
            content: "";
            position: absolute;
            top: 0px;
            left: 0px;
            bottom: 0px;
            background: #04680e;
            animation: box-2 2100ms cubic-bezier(0.16, 0.84, 0.44, 1) infinite;
            animation-delay: 1150ms;
        }

        @keyframes box-1 {
            0% {
                left: -35%;
                right: 100%;
            }

            60%,
            100% {
                left: 100%;
                right: -90%;
            }
        }

        @keyframes box-2 {
            0% {
                left: -200%;
                right: 100%;
            }

            60%,
            100% {
                left: 107%;
                right: -8%;
            }
        }
    </style>

    <div wire:loading.class="bar">
        <div></div>
    </div>

</div>
