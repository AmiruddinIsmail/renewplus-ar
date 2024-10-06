import { Home, ShoppingCart, Users } from "lucide-react";

const icons = {
    Home: Home,
    Users: Users,
    ShoppingCart: ShoppingCart,
};

const SidebarIcon = ({ name }: { name: string }) => {
    const Icon = icons[name as keyof typeof icons];
    return <Icon className="h-4 w-4" />;
};

export { SidebarIcon, icons };
