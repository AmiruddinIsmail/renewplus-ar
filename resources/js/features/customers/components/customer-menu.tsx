import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from "@/components/ui/navigation-menu";
import { Link } from "@inertiajs/react";
import {
    ChartBarStacked,
    CreditCard,
    File,
    FileStack,
    LucideDollarSign,
    User,
} from "lucide-react";

type PropsType = {
    title: string;
    id: number;
    activeName?: string;
};

export default function CustomerMenu({
    title,
    id,
    activeName = "Summary",
}: PropsType) {
    const items = [
        {
            title: "Summary",
            url: route("customers.show", id),
            icon: User,
        },
        {
            title: "Invoices",
            url: route("customers.invoices", id),
            icon: File,
            only: ["table"],
        },
        {
            title: "Payments",
            url: route("customers.payments", id),
            icon: LucideDollarSign,
        },
        {
            title: "Credit Notes",
            url: "#",
            icon: CreditCard,
        },
        {
            title: "Statement Of Account",
            url: "#",
            icon: ChartBarStacked,
        },
        {
            title: "Aging Report",
            url: "#",
            icon: FileStack,
        },
    ];

    return (
        <>
            <NavigationMenu>
                <NavigationMenuList>
                    {items.map((item) => (
                        <NavigationMenuItem key={item.title}>
                            <Link href={item.url} as="button">
                                <NavigationMenuLink
                                    active={item.title === activeName}
                                    className={navigationMenuTriggerStyle()}
                                >
                                    <item.icon className="mr-2 h-4 w-4" />
                                    {item.title}
                                </NavigationMenuLink>
                            </Link>
                        </NavigationMenuItem>
                    ))}
                </NavigationMenuList>
            </NavigationMenu>
        </>
    );
}
