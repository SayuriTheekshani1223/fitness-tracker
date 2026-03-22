function saveGoals(){

    let steps = document.getElementById("stepsGoal").value
    let calories = document.getElementById("caloriesGoal").value
    
    localStorage.setItem("stepsGoal", steps)
    localStorage.setItem("caloriesGoal", calories)
    
    displayGoals()
    
    }
    
    
    function displayGoals(){
    
    let steps = localStorage.getItem("stepsGoal") || 8000
    let calories = localStorage.getItem("caloriesGoal") || 1500
    
    document.getElementById("displaySteps").innerText = steps
    document.getElementById("displayCalories").innerText = calories
    
    }
    
    
    displayGoals()