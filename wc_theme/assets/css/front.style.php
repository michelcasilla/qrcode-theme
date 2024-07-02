
body{
    display: flex;
    height: 100vh;
    width: 100vw;
    flex-direction: column;
    padding: 0;
    margin: 0;
    font-family: verdana
}
event{
    flex: 1;
    display: flex;
    flex-direction: column;
    max-width: 100vw;
    overflow-x: hidden;
}
event--header {
    display: flex;
    min-height: 45vh;
    flex-direction: column;
    color: white;
    background-image: url(https://d2poexpdc5y9vj.cloudfront.net/themes/3.0/bg/abstract19.jpg);
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    align-items: center;
    justify-content: center;
    position: relative;
}

event--header:after {
    content: " ";
    background: rgba(5, 7, 64, 0.63);
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: 0;
}

event--content {
    max-width: 900px;
    width: 100%;
    margin: auto;
    flex: 1;
/*    max-height: 90%;*/
    display: flex;
}

.padding{
padding: 20px;
}

.flex-dir-col {
    flex-direction: column;
}

event--header--info {
    text-align: center;
    flex: 1;
    z-index: 1;
    justify-content: center;
    display: flex;
}

.event--title {
    margin: 0;
    font-size: 2rem;
    font-weight: 100;
}

event--tabs {
    display: flex;
    background: #eaeaea;
    color: black;
    max-width: 900px;
    width: 100%;
    font-size: 14px;
    border-radius: 4px 4px 0px 0px;
    flex: 1;
    max-height: 50px;
    z-index: 1;
    overflow: hidden;
}

event--tabs tab {
    padding: 14px 20px;
    flex: 1;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}
event--tabs tab:hover,
event--tabs tab.active{
    background: white;
    cursor: pointer;
}
h3{
  font-weight: 100  
}
.event--buy-tickets {
    padding: 14px 30px;
    border: 0;
    border-radius: 4px;
    background: #67c352;
    color: white;
}
p{
    line-height: 150%;
}
event--cart-info {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 900px;
    margin: auto;
    height: auto
}

card--left {
    flex: 1;
    display: flex;
    flex-direction: column;
}

card--price {
    font-size: 1.2rem;
    font-weight: bold;
}


event-card {
    flex: 1;
    display: flex;
    flex-direction: row;
    padding: 20px;
    box-shadow: 0px 0px 4px 2px rgba(12, 12, 85, 0.16);
    border:solid 1px rgba(11, 21, 75, 0.49);
    border-radius: 4px;
    line-height: 150%
}

card--right {
/*    flex: 1;*/
    align-items: flex-start;
    justify-content: flex-end;
    display: flex;
    padding: 8px;
}

card--right input.form-control {
    max-width: 100px;
}

card--title {
    font-weight: bold;
}

card--date {
    font-size: 12px;
}

.card--group--title {
    font-weight: 100;
}

event--location {
    display: flex;
    background: #ebebeb;
    flex: 1;
    max-height: 30vh;
    align-items: center;
    justify-content: center
}

.form-control {
    padding: 10px;
    font-size: 16px;
    border: solid 1px #ccc;
}

.paypalBtn{
    height: 40px;;
}