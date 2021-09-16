import React, {useContext, useEffect, useState} from 'react'
import {MyContext} from '../contexts/MyContext'
import axios from 'axios'

// Importing the Login & Register Componet
import Login from './Login'
import Register from './Register'

function Home(){

    const {rootState,logoutUser} = useContext(MyContext);
    const {isAuth,theUser,showLogin} = rootState;
    const [users, setUsers] = useState([])

    useEffect(()=>{
        axios.get('localhost:8080/php-login-registration-api/users.php')
        .then(response=>{
            console.log(response)
            setUsers(response.data.data)
        }).catch(error=>{
            console.log(error)
        })
    })

    // If user Logged in
    if(isAuth)
    {
        return(
            <div className="userInfo">
                <div className="_img"><span role="img" aria-label="User Image">👩</span></div>
                <h1>{theUser.name}</h1>
                <div className="_email"><span>{theUser.email}</span></div>
                <button onClick={logoutUser}>Logout</button>
                <table>
                    {
                        users.map(user=> <tr key={user.id}><td>{user.id}</td><td>{user.name}</td><td>{user.email}</td></tr>)
                    }
                </table>
            </div>
        )
    }
    // Showing Login Or Register Page According to the condition
    else if(showLogin){
        return <Login/>;
    }
    else{
        return <Register/>;
    }   
}

export default Home;