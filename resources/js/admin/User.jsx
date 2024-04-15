import React, { useEffect } from "react";
import axios from 'axios';
const User = () => {
    useEffect(() => {
        axios.get('/api/users').then((res) => {
            console.log('users', res)
        })
    }, [])

    return (
        <div> User</div>
    )
}
export default User