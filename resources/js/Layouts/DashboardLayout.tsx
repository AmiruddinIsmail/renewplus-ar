import { CircleUser, Menu, Package2, Search } from "lucide-react";
import { Link, usePage } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";
import { Input } from "@/Components/ui/input";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { PropsWithChildren, useEffect } from "react";
import { cn } from "@/lib/utils";
import { useToast } from "@/hooks/use-toast";
import { Toaster } from "@/Components/ui/toaster";
import { SidebarIcon } from "@/Components/SidebarIcon";

export default function DashboardLayout({ children }: PropsWithChildren) {
    const { url } = usePage();
    const { menu, auth } = usePage().props;
    const { toast } = useToast();

    useEffect(() => {
        (window as any).Echo.private(`user.export.${auth.user.id}`).listen(
            "UserExportEvent",
            (event: any) => {
                toast({
                    title: "User Exported",
                    description: "User has been exported successfully",
                    variant: "success",
                });
            }
        );
    }, []);

    return (
        <div className="grid min-h-screen w-full md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]">
            {/* sidebar */}
            <div className="hidden border-r bg-muted/40 md:block">
                <div className="flex h-full max-h-screen flex-col gap-2">
                    <div className="flex h-14 items-center border-b px-4 lg:h-[60px] lg:px-6">
                        <Link
                            href="/"
                            className="flex items-center gap-2 font-semibold"
                        >
                            <Package2 className="h-6 w-6" />
                            <span className="">Acme Inc</span>
                        </Link>
                    </div>
                    <div className="flex-1">
                        <nav className="grid items-start px-2 text-sm font-medium lg:px-4">
                            {menu.map((item, index) => (
                                <Link
                                    href={item.route}
                                    key={index}
                                    className={cn(
                                        "flex items-center gap-3 rounded-lg px-3 py-2 transition-all text-muted-foreground hover:text-primary",
                                        url === item.url &&
                                            "text-primary bg-muted"
                                    )}
                                >
                                    {item.icon && (
                                        <SidebarIcon name={item.icon} />
                                    )}

                                    {item.label}
                                    {item.badge && (
                                        <Badge className="ml-auto flex h-6 w-6 shrink-0 items-center justify-center rounded-full">
                                            {item.badge}
                                        </Badge>
                                    )}
                                </Link>
                            ))}
                        </nav>
                    </div>
                </div>
            </div>
            <div className="flex flex-col">
                <header className="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6">
                    {/* mobile menu */}
                    <Sheet>
                        <SheetTrigger asChild>
                            <Button
                                variant="outline"
                                size="icon"
                                className="shrink-0 md:hidden"
                            >
                                <Menu className="h-5 w-5" />
                                <span className="sr-only">
                                    Toggle navigation menu
                                </span>
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" className="flex flex-col">
                            <nav className="grid gap-2 text-lg font-medium">
                                <Link
                                    href="#"
                                    className="flex items-center gap-2 text-lg font-semibold"
                                >
                                    <Package2 className="h-6 w-6" />
                                    <span className="sr-only">Acme Inc</span>
                                </Link>
                                {menu.map((item, index) => (
                                    <Link
                                        href={item.route}
                                        key={index}
                                        className={cn(
                                            "flex items-center gap-3 rounded-lg px-3 py-2 transition-all text-muted-foreground hover:text-primary",
                                            url === item.url &&
                                                "text-primary bg-muted"
                                        )}
                                    >
                                        {item.icon && (
                                            <SidebarIcon name={item.icon} />
                                        )}

                                        {item.label}
                                        {item.badge && (
                                            <Badge className="ml-auto flex h-6 w-6 shrink-0 items-center justify-center rounded-full">
                                                {item.badge}
                                            </Badge>
                                        )}
                                    </Link>
                                ))}
                            </nav>
                        </SheetContent>
                    </Sheet>
                    {/* header */}
                    <div className="w-full flex-1">
                        <form>
                            <div className="relative">
                                <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                                <Input
                                    type="search"
                                    disabled
                                    placeholder="Global search feature in development..."
                                    className="w-full appearance-none bg-background pl-8 shadow-none md:w-2/3 lg:w-1/3"
                                />
                            </div>
                        </form>
                    </div>

                    {/* user menu */}
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button
                                variant="secondary"
                                size="icon"
                                className="rounded-full"
                            >
                                <CircleUser className="h-5 w-5" />
                                <span className="sr-only">
                                    Toggle user menu
                                </span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>
                                {auth.user.name}
                            </DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>Settings</DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>
                                <Link
                                    as="button"
                                    method="post"
                                    href={route("logout")}
                                >
                                    Logout
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </header>

                <main className="flex flex-1 flex-col gap-4 p-4 lg:gap-2 lg:px-6">
                    {children}
                </main>
                <Toaster />
            </div>
        </div>
    );
}
