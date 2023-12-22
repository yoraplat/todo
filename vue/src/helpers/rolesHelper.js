export function checkRoles(user, requiredRoles) {
    if (!user || !user.roles || !Array.isArray(user.roles)) {
        return false;
    }

    return requiredRoles.some(requiredRole => {
        return user.roles.some(userRole => userRole.name === requiredRole);
    });
}
